<?php
/*
class Posts {
	private $db;
	
	public function __construct($database){
		$this->db = $database;
	}
}*/

function get_all(){
	global $db;
	
	$page = 0;
    $offset = 0;
	$limit = 10;
	
	if(isset($_GET['page'])) {
        $page = $_GET['page'] + 1;
        $offset = $limit * $page ;
    }
	
	$sql_query 	= "SELECT * FROM post ORDER BY pubdate DESC LIMIT $offset, $limit";
	//print_r ($sql_query);
	$result 	= $db->query($sql_query);
	while ($row = $result->fetch_assoc()) {
		$results[] = $row;
	}	
	return $results;
}

function num_all(){
	global $db;	
	if ($result = $db->query("SELECT * FROM post")) {
		$row_cnt = $result->num_rows;
	}
	return $row_cnt;	
	$result->close();
	$db->close();
}

function get_post($id){
	global $db;
	$sql_query = "SELECT * FROM post WHERE id ='{$id}'";
	//echo $sql_query;	
	if ($result = $db->query($sql_query)) {
		$row = $result->fetch_assoc();
	}	
	return $row;	
	$result->close();
	$db->close();
}

function get_the_content($id){
	$post = get_post( $id );
	$desc = isset( $post['content'] ) ? $post['content'] : '';
	//$desc = preg_replace('/\s+/', ' ', $desc);
	
	return nl2br($desc);
}

function get_the_title($id){
	$post = get_post( $id );
	$title = isset( $post['title'] ) ? $post['title'] : '';
	return $title;
}

function get_the_permalink($id, $ext='.html'){	
	$slug = slugify( get_the_title($id) );	
	$newUrl = base_url().'/'.$id.'-'.$slug.$ext;
	return $newUrl;
}

function get_source_permalink($id){
	$post = get_post( $id );
	$url = isset( $post['url'] ) ? $post['url'] : '';
	return $url;
}

function get_the_excerpt($id,$maxLength=100){
	$post = get_post( $id );
	$desc = isset( $post['content'] ) ? $post['content'] : '';
	return get_excerpt(strip_tags(trim($desc)),$maxLength);
}

function del_duplicate(){
	/*SELECT a.* FROM post AS a INNER JOIN (
      SELECT url FROM post GROUP BY url HAVING COUNT( * ) > 1
   ) AS b ON b.url = a.url*/
}

function get_the_date($format='Y-m-d H:i:s', $id){
	$post = get_post( $id );
	$pubdate = isset( $post['pubdate'] ) ? $post['pubdate'] : '';
	
	$new_date = the_date($format, $pubdate);
	return $new_date;
}