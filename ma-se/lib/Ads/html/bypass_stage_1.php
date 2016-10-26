<html>
<head>
    <style type="text/css">
        .m {
            display: table;
            height: 100%;
            width: 100%;
        }
        .w {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            width: 100%;
        }

        .spinner {
            margin: 100px auto;
            width: 50px;
            height: 40px;
            text-align: center;
            font-size: 10px;
        }

        .spinner > div {
            background-color: #333;
            height: 100%;
            width: 6px;
            display: inline-block;

            -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
        }

        .spinner .rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }

        .spinner .rect3 {
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;
        }

        .spinner .rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }

        .spinner .rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }

        @-webkit-keyframes sk-stretchdelay {
            0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
            20% { -webkit-transform: scaleY(1.0) }
        }

        @keyframes sk-stretchdelay {
            0%, 40%, 100% {
                transform: scaleY(0.4);
                -webkit-transform: scaleY(0.4);
            }  20% {
                   transform: scaleY(1.0);
                   -webkit-transform: scaleY(1.0);
               }
        }

    </style>

</head>
<body>

<div class="m">
    <div class="w">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>


        <script type="text/javascript">
            if(history && typeof history.pushState === 'function') {
                history.pushState({}, "PP1", "<?php echo $deliver_url; ?>");
                history.pushState({}, "PP2", "<?php echo $deliver_url; ?>");
                window.history.back();
                setTimeout(function(){
                    window.location.reload(true);
                },100);
            } else {
                document.write('<iframe src="<?php echo $deliver_url; ?>" style="visibility: hidden;"></iframe>');
            }
        </script>

    </div>
</div>


</body>
</html>
