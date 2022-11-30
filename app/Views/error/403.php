<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?= $data['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="<?= $data['tag']['info']; ?>" />
        <style type="text/css">
            body {
                font-size: 14px;
                color: #777777;
                font-family: arial;
                text-align: center;
                background-color: #000c2f;
            }
            h1 {
                font-size: 180px;
                color: #99a7af;
                margin: 70px 0 0 0;
            }
            h2 {
                color: #de6c5d;
                font-family: arial;
                font-size: 20px;
                font-weight: bold;
                letter-spacing: -1px;
                margin: -3px 0 39px;
            }
            p {
                width: 320px;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
                margin-top: 30px;
            }
            div {
                width: 320px;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }
            a:link {
                color: #34536a;
            }
            a:visited {
                color: #34536a;
            }
            a:active {
                color: #34536a;
            }
            a:hover {
                color: #34536a;
            }
        </style>
    </head>

    <body>
        <img src="<?= URLROOT . '/uploads/error/403-error-forbidden.svg'; ?>" width="650" alt="404 error" />
        <h2>Forbidden</h2>
        <div>
            Unfortunately, you do not have permission to view this
        </div>
    </body>
</html>
