<style>
    .fxt-form-content {
        position: relative;
        padding: 20px;
        border-radius: 10px;
        overflow: hidden;
    }

    .fxt-form-content .border {
        position: absolute;
        height: 5px;
        width: 50%;
        /* background: linear-gradient(90deg, #FF4500, #FFA500); */
        background: linear-gradient(315deg, #03a9f4, #ff0058);
        box-shadow: 0 0 10px rgba(30, 144, 255, 0.8);
        animation: borderMove 3s linear infinite;
    }

    .fxt-form-content .border-top {
        top: 0;
        left: 50%;
        transform-origin: left;
    }

    .fxt-form-content .border-bottom {
        bottom: 0;
        right: 50%;
        transform-origin: right;
        animation: borderMoveReverse 3s linear infinite;
    }

    .fxt-form-content .border-left {
        width: 5px;
        height: 50%;
        left: 0;
        bottom: 50%;
        transform-origin: bottom;
        animation: borderMoveVerticalReverse 3s linear infinite;
    }

    .fxt-form-content .border-right {
        width: 5px;
        height: 50%;
        right: 0;
        top: 50%;
        transform-origin: top;
        animation: borderMoveVertical 3s linear infinite;
    }

    @keyframes borderMove {
        0% { width: 0; left: 50%; }
        50% { width: 50%; left: 50%; }
        66% { width: 50%; left: 50%; }
        100% { width: 0; left: 100%; }
    }

    @keyframes borderMoveReverse {
        0% { width: 0; right: 50%; }
        50% { width: 50%; right: 50%; }
        66% { width: 50%; right: 50%; }
        100% { width: 0; right: 100%; }
    }

    @keyframes borderMoveVertical {
        0% { height: 0; top: 50%; }
        50% { height: 50%; top: 50%; }
        66% { height: 50%; top: 50%; }
        100% { height: 0; top: 100%; }
    }

    @keyframes borderMoveVerticalReverse {
        0% { height: 0; bottom: 50%; }
        50% { height: 50%; bottom: 50%; }
        66% { height: 50%; bottom: 50%; }
        100% { height: 0; bottom: 100%; }
    }

    /* responsive css */
    @media only screen and (max-width: 480px) and (-webkit-min-device-pixel-ratio: 1.5), (max-width: 480px) and (min-resolution: 144dpi) {
        a.fxt-logo img {
            max-width: 100% !important;
        }
    }
</style>