<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <META http-equiv="Content-Type" content="text/html; charset=UTF-8"><META name="viewport" content="width=device-width" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <style type="text/css">/* Email Template Styles */
             
               
                @import url('https://fonts.googleapis.com/css?family=Montserrat:600,700&display=swap');

                /* /\/\/\/\/\/\/\/\/ CLIENT-SPECIFIC STYLES /\/\/\/\/\/\/\/\/ */
                .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td,.ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
                #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
                table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
                img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
                body, table, td, p, a, li, span, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */

                /* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
                body{
                    font-family: Montserrat;
                    margin:0;
                    padding:0;
                }
                img{
                    border:0 none;
                    height:auto;
                    line-height:100%;
                    outline:none;
                    text-decoration:none;
                }
                a{
                    text-decoration: none;
                    outline: none;
                }
                a img{
                    border:0 none;
                }
                table, td {
                    border-collapse:collapse !important;
                }

                table {
                    background-color: transparent;
                    border-top: 0;
                    border-bottom: 0;

                    /*line-height:150%;*/
                    max-width: 600px !important;
                }

                tbody, td {
                    padding: 0;
                    margin: 15px;
                    vertical-align: top;
                }
                tr, img, p, h2 {
                    margin: 0;
                    padding: 0;
                }
                /* END RESET STYLES */
                /* HERE STARTS THE STYLES THAT ARE ALSO INLINE */
                table#template {
                    margin:0 auto; padding:0; width:initial !important; max-width: 600px !important;}

                table#template > tbody{
                    /* only affect the first tbody on first level so that it doesn't affect all the tbody descendants*/
                    color: #2D2A26;
                    font-size: 15px;
                }
                table.container{
                    width: 600px !important;

                }
                .logo_producto {
                    width: 30% !important;
                }
                /* END*/
                /* Responsive */
                @media only screen and (max-width: 480px){
                    td.container-column {
                        display: block !important;
                        width: 100% !important;
                        margin: auto !important;
                    }
                    .logo_producto {
                    width: 30% !important;
                }
                }
                @media only screen and (max-width: 600px){
                    img {
                        width: 100% !important;
                    }
                    table.component {
                        width: 100% !important;
                    }
                    table.container {
                        width: 100% !important;
                    }
                    .logo_producto {
                    width: 30% !important;
                }
                }
                @media only screen and (max-width: 600px){
                    .social-component img {
                        width: auto !important;
                    }
                    .logo_producto {
                    width: 30% !important;
                }
                }
            </style>
    </head>
    <body style="background-image:none;background-color:rgba(0, 0, 0, 0);">
        <table align="center" class="template" id="template" style="border-spacing:0px;text-align:center; width:600px"><!-- EDITOR-CONTENT-BEGIN -->
            <tbody class="ui-sortable" link-color="#606060" style="display: block; background-color: rgb(255, 255, 255); color: rgb(96, 96, 96); border: 0px none rgb(0, 0, 0); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;" title-color="#000000">

                @yield('content')
                    
            </tbody>
            <!-- EDITOR-CONTENT-END -->
        </table>
    </body>
</html>
