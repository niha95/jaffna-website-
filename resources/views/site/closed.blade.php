<!DOCTYPE html>
<html>
<head>
    <title>{{ trans('messages.be_right_back') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0 auto;
            padding: 0 20px;
            color: #505860;
            display: table;
            max-width: 992px;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
            font-weight: 100;
            font-family: 'Lato', sans-serif;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">{{ trans('messages.be_right_back') }}</div>
        <div>{!! $message !!}</div>
    </div>
</div>
</body>
</html>
