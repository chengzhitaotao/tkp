<?php
/**
 * xml解析的工具类
 */
namespace Home\util;
class XMLParse{
    public static function parseDBXML(){
        //得到根节点
        $sx = simplexml_load_file(dirname(__DIR__)."/config/db.xml");
        
        //获取某个节点下所有子节点   返回数组
        $children = $sx->children();
        $pdoMysql = array((string)$children[0]->dsn,(string)$children[0]->username,(string)$children[0]->userpass);
        
        // print_r($pdoMysql);
        
        return $pdoMysql;
    }
    // $parser = xml_parser_create();
    // xml_set_element_handler($parser, "tagBegin", "tagEnd");
    // xml_set_character_data_handler($parser, "tagText");
    // $str = file_get_contents("db.xml");
    // xml_parse($parser, $str);
    
    
    // function tagBegin($parse, $element_name,$element_attrs){
    
    // }
    // function tagEnd($parse,$element_name){
    
    // }
    // function tagText($parse,$data){
    
    // }
    
    /**
     * 通过元素名称获得元素数组
     */
    // //初始化解析器
    // $document = new DOMDocument();
    // //加载xml文件
    // $document->load("db.xml");
    
    // $dsn = $document->getElementsByTagName("dsn");
    // $username = $document->getElementsByTagName("username");
    // $userpass = $document->getElementsByTagName("userpass");
    // //获取元素的文本内容
    // echo $dsn->item(0)->nodeValue;
    // echo $username->item(0)->nodeValue;
    // echo $userpass->item(0)->nodeValue;
    
}

?>