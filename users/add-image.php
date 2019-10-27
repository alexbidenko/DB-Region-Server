<?php
function can_upload($file){
	if($file['name'] == '')
		return 'Вы не выбрали файл.';
		
	if($file['size'] == 0)
		return 'Файл слишком большой.';
		
	$getMime = explode('.', $file['name']);
	$mime = strtolower(end($getMime));
	$types = array('jpg', 'png', 'bmp', 'jpeg');
		
	if(!in_array($mime, $types))
		return 'Недопустимый тип файла.';
	
	return true;
}
  
function make_upload($file) {
	$getMime = explode('.', $file['name']);
	$mime = strtolower(end($getMime));
    $img_name = rand(1000, 9999).date('U').'.'.$mime;
	copy($file['tmp_name'], '../images/'.$img_name);
    echo "http://hackathon.tw1.ru/images/".$img_name;
}
            
$check = can_upload($_FILES['image']);
    
if($check === true){
    make_upload($_FILES['image']);
}
?>