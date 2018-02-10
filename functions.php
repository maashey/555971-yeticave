<?php
function render_template($template_path, $vars)
{
		//Проверяем, что файл шаблона, переданный в аргументе, существует. Если не существует, то функция вернет пустую строку
    if (!is_file($template_path)){  
        return '';    	
    }
    extract($vars); // extract делает из массива набор переменных в локальной области видимости
    ob_start();  //Включение буферизации вывода
    include($template_path); //переменные из extract будут видны внутри подключаемого файла
    return ob_get_clean();  //возвращаем буфер и очищаем
}