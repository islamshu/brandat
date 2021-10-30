<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Khand:wght@500&display=swap');
        *{
            margin:0;
            padding: 0;
            box-sizing: border-box;
        }
        body  {
            height: 100vh;
            display: flex;
            font-size: 14px;
            text-align: center;
            justify-content: center;
            align-items: center;
            font-family: 'Khand', sans-serif;
        }

        .wrapperAlert {
            width: 500px;
            height: 400px;
            overflow: hidden;
            border-radius: 12px;
            border: thin solid #ddd;
        }

        .topHalf {
            width: 100%;
            color: white;
            overflow: hidden;
            min-height: 250px;
            position: relative;
            padding: 40px 0;
            background: rgb(0,0,0);
            background: -webkit-linear-gradient(45deg, #980101, #eba0a0);
        }

        .topHalf p {
            margin-bottom: 30px;
        }
        svg {
            fill: white;
        }
        .topHalf h1 {
            font-size: 2.25rem;
            display: block;
            font-weight: 500;
            letter-spacing: 0.15rem;
            text-shadow: 0 2px rgba(128, 128, 128, 0.6);
        }

        /* Original Author of Bubbles Animation -- https://codepen.io/Lewitje/pen/BNNJjo */
        .bg-bubbles{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        li{
            position: absolute;
            list-style: none;
            display: block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.15);/* fade(green, 75%);*/
            bottom: -160px;

            -webkit-animation: square 20s infinite;
            animation:         square 20s infinite;

            -webkit-transition-timing-function: linear;
            transition-timing-function: linear;
        }
        li:nth-child(1){
            left: 10%;
        }
        li:nth-child(2){
            left: 20%;

            width: 80px;
            height: 80px;

            animation-delay: 2s;
            animation-duration: 17s;
        }
        li:nth-child(3){
            left: 25%;
            animation-delay: 4s;
        }
        li:nth-child(4){
            left: 40%;
            width: 60px;
            height: 60px;

            animation-duration: 22s;

            background-color: rgba(white, 0.3); /* fade(white, 25%); */
        }
        li:nth-child(5){
            left: 70%;
        }
        li:nth-child(6){
            left: 80%;
            width: 120px;
            height: 120px;

            animation-delay: 3s;
            background-color: rgba(white, 0.2); /* fade(white, 20%); */
        }
        li:nth-child(7){
            left: 32%;
            width: 160px;
            height: 160px;

            animation-delay: 7s;
        }
        li:nth-child(8){
            left: 55%;
            width: 20px;
            height: 20px;

            animation-delay: 15s;
            animation-duration: 40s;
        }
        li:nth-child(9){
            left: 25%;
            width: 10px;
            height: 10px;

            animation-delay: 2s;
            animation-duration: 40s;
            background-color: rgba(white, 0.3); /*fade(white, 30%);*/
        }
        li:nth-child(10){
            left: 90%;
            width: 160px;
            height: 160px;

            animation-delay: 11s;
        }

        @-webkit-keyframes square {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-500px) rotate(600deg); }
        }
        @keyframes square {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-500px) rotate(600deg); }
        }

        .bottomHalf {
            align-items: center;
            padding: 35px;
        }
        .bottomHalf p {
            font-weight: 500;
            font-size: 1.05rem;
            margin-bottom: 20px;
        }

        button {
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 12px;
            padding: 10px 18px;
            background-color: #019871;
            text-shadow: 0 1px rgba(128, 128, 128, 0.75);
        }
        button:hover {
            background-color: #85ddbf;
        }
    </style>
</head>
<body>
<div class="wrapperAlert">
    <div class="contentAlert">
        <div class="topHalf">
            <p><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    width="100" viewBox="0 0 416.979 416.979" style="enable-background:new 0 0 416.979 416.979;"
                    xml:space="preserve">
                <g>
                    <path d="M355.914,61.065c-81.42-81.42-213.428-81.42-294.849,0s-81.421,213.427,0,294.849c81.42,81.42,213.428,81.42,294.849,0
                        C437.334,274.492,437.334,142.485,355.914,61.065z M312.525,258.763c4.454,4.454,4.454,11.675,0,16.129l-37.632,37.632
                        c-4.454,4.454-11.675,4.453-16.13,0l-50.273-50.275l-50.275,50.275c-4.453,4.455-11.674,4.453-16.128,0l-37.632-37.632
                        c-4.454-4.454-4.453-11.674,0-16.127l50.275-50.276l-50.275-50.275c-4.453-4.454-4.453-11.675,0-16.128l37.633-37.632
                        c4.454-4.454,11.675-4.454,16.127,0l50.275,50.275l50.274-50.275c4.454-4.454,11.675-4.454,16.129,0l37.632,37.632
                        c4.453,4.454,4.454,11.675,0,16.128l-50.275,50.275L312.525,258.763z"/>
                </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></p>
            <h1>الغاء العملية</h1>
        </div>
        <div class="bottomHalf">
            <p>تمت الغاء العملية بنجاح</p>
            <p>شكرا لك</p>
        </div>
    </div>
</div>
<script>
    function waitForBridge() {
        if (window.ReactNativeWebView.postMessage.length !== 1)
            setTimeout(waitForBridge, 200);
        else
            window.ReactNativeWebView.postMessage('abc');
    }
    window.onload = waitForBridge;
</script>
</body>
</html>