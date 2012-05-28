<?php
$root = $constant->_ROOT_;

if( isset( $constant->jqueryMobile ) and $constant->jqueryMobile == true ){
    $add = <<< END
    <link href="http://libs.ipsleoz.com/css/jquery.mobile-1.0a4.1.min.css" type ="text/css" rel="stylesheet"  />
    <script src="http://libs.ipsleoz.com/jquery-mobile/jquery.mobile-1.0a4.1.min.js"></script>
END;
}else{
    $add = '<meta name="viewport" content="width=device-width, initial-scale=1.0,  maximum-scale=1.0, user-scalable=no" />';
}

echo  <<< END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type"  content="text/html; charset=UTF-8" />
    <meta name="robots" content="index,follow" />
    <meta name="description" content="{@description}" /> 
    <meta name="keywords" content="{@keywords}" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="http://libs.ipsleoz.com/css/reset.css" type="text/css" rel="stylesheet" />
    <link href="http://libs.ipsleoz.com/google-syntax/styles/google_syntax_highlighter.css" type="text/css" rel="stylesheet" />
    <script src="http://libs.ipsleoz.com/jquery/1.5.2/jquery.min.js"></script>
    {$add}
    <script type="text/javascript" src="http://libs.ipsleoz.com/javascript/json2.js"></script> 
    {@javascript}
    {@css}
    <title>{@title}</title>
</head>
END;
