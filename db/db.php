<?php

require_once dirname(__FILE__) . '/config.php';

global $link;

function result_array($table, $cond = array(), $offset = 0, $limit = 0, $sort = array())
{
	global $link;
	conn_db();
	$query = "SELECT * FROM $table";
	$where = array();

	if (!empty($cond)) {
		if (is_array($cond)) {
			foreach ($cond as $k => $v) {
				$where[] = "`$k` = '" . mysqli_real_escape_string($link, $v) . "'";
			}

			$query .= " WHERE " . join(' AND ', $where);
		} elseif (is_string($cond)) {
			$query .= " WHERE " . $cond;
		}
	}

	if (!empty($sort)) {
		$order = array();
		foreach ($sort as $k => $v) {
			$order[] = "$k $v";
		}

		$query .= " ORDER BY " . join(', ', $order);
	}

	if ($offset + $limit > 0) {
		$query .= " LIMIT $offset, $limit";
	}

	$result = array();
	if ($raw = mysqli_query($link, $query)) {
		while ($row = mysqli_fetch_assoc($raw)) {
			$result[] = $row;
		}

	} else {
		die($query . "<br />\n" . mysqli_error($link));
	}

	close_db();
	return $result;
}

function multi_result_query($query)
{
	global $link;
	conn_db();

	$result = array();
	if ($raw = mysqli_query($link, $query)) {
		while ($row = mysqli_fetch_assoc($raw)) {
			$result[] = $row;
		}

	} else {
		die($query . "<br />\n" . mysqli_error($link));
	}

	close_db();
	return $result;
}

function row_array($table, $cond = array())
{
	global $link;
	conn_db();

	$query = "SELECT * FROM $table";
	$where = array();

	if (!empty($cond)) {
		foreach ($cond as $k => $v) {
			$where[] = "`$k` = '" . mysqli_real_escape_string($link, $v) . "'";
		}

		$query .= " WHERE " . join(' AND ', $where);
	}

	if ($raw = mysqli_query($link, $query)) {
		$result = mysqli_fetch_assoc($raw);
	} else {
		die(mysqli_error($link));
	}

	close_db();
	return $result;
}

function insert($table, $data = array())
{
	global $link;
	conn_db();

	array_walk($data, function (&$row) use ($link) {
		$row = mysqli_real_escape_string($link, $row);
	});

	$query = "INSERT INTO $table (`" . implode('`,`', array_keys($data)) . "`) VALUES ('" . implode("','", $data) . "')";
	mysqli_query($link, $query) or die(mysqli_error($link) . $query);
	$insert_id = mysqli_insert_id($link);
	close_db();

	return $insert_id;
}

function delete($table, $where = '')
{
	global $link;
	conn_db();

	if (!empty($where)) {
		if (is_string($where)) {
			$where .= ' WHERE ' . $where;
		} elseif (is_array($where)) {
			$cond = array();
			foreach ($where as $k => $v) {
				$cond[] = "`$k` = '" . mysqli_real_escape_string($link, $v) . "'";
			}

			$where = ' WHERE ' . join(' AND ', $cond);
		}
	}

	mysqli_query($link, "DELETE FROM $table $where") or die(mysqli_error($link));
	close_db();
}

function update($table, $data = array(), $where = array())
{
	global $link;
	conn_db();

	$update = array();

	foreach ($data as $k => $v) {
		$update[] = '`' . $k . '` = ' . "'" . mysqli_real_escape_string($link, $v) . "'";
	}

	$arr_where = array();

	foreach ($where as $k => $v) {
		$arr_where[] = '`' . $k . '` = ' . "'" . mysqli_real_escape_string($link, $v) . "'";
	}

	if (!empty($arr_where)) {
		$str_where = ' WHERE ' . join(' AND ', $arr_where);
	} else {
		$str_where = '';
	}

	mysqli_query($link, "UPDATE $table SET " . implode(',', $update) . " $str_where") or die(mysqli_error($link));
	close_db();
}

function upsert($table, $data = array())
{
	global $link;
	conn_db();

	array_walk($data, function ($row) {
		global $link;
		return mysqli_real_escape_string($link, $row);
	});

	mysqli_query($link, "REPLACE INTO $table (`" . implode('`,`', array_keys($data)) . "`) VALUES ('" . implode("','", $data) . "')") or die(mysqli_error($link));
	close_db();
}

function count_all($table, $cond = array())
{
	global $link;
	conn_db();

	$query = "SELECT COUNT(*) FROM $table";
	$where = array();

	if (!empty($cond)) {
		foreach ($cond as $k => $v) {
			$where[] = "`$k` = '" . mysqli_real_escape_string($link, $v) . "'";
		}

		$query .= " WHERE " . join(' AND ', $where);
	}

	if ($raw = mysqli_query($link, $query)) {
		$result = mysqli_fetch_row($raw);
	} else {
		die(mysqli_error($link));
	}

	close_db();
	return (int) $result[0];
}

function conn_db()
{
	global $link;
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die(mysqli_error($link));
	mysqli_select_db($link, DB_NAME) or die(mysqli_error($link));

	if (!empty($_POST)) {
		$_POST = sanitize($_POST);
	}

}

function sanitize($data)
{
	global $link;
	foreach ($data as $k => $v) {
		if (is_string($v)) {
			$data[$k] = mysqli_real_escape_string($link, $v);
		} elseif (is_array($v)) {
			$data[$k] = sanitize($v);
		}

	}

	return $data;
}

function close_db()
{
	global $link;
	mysqli_close($link);
}

function slug($str)
{
	$str = strtolower($str);
	$str = preg_replace('/([^a-z0-9]+)/', '-', $str);
	$str = preg_replace('/(-+)/', '-', $str);
	return $str;
}
