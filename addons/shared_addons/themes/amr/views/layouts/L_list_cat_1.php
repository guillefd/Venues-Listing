<!DOCTYPE html>
<html lang="en">
    <head>
            {{ theme:partial name="listview/metadata" }}
    </head>
    <body id="body_homelist">
            {{ integration:analytics }}
            {{ theme:partial name="listview/header" }}
            {{ template:body }}
            {{ theme:partial name="footer" }}                      
            {{ theme:partial name="listview/footmetadata" }}               
    </body>
</html>