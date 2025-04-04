@extends('layouts.master')

@section('content')
    <style>
        .failure-animation {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .crossmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #ff4444;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #ff4444;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .crossmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #ff4444;
            fill: none;
            animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .crossmark__cross {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {

            0%,
            100% {
                transform: none;
            }

            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px #ff444400;
            }
        }
    </style>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="mb-4">
                    <div class="failure-animation">
                        <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="crossmark__cross" fill="none" d="M16 16 36 36 M36 16 16 36" />
                        </svg>
                    </div>
                </div>
                <h2 class="fw-bold mb-3">Thất bại!</h2>
                <p class="lead mb-4">Đã xảy ra lỗi trong quá trình xử lý yêu cầu của bạn.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/try-again" class="btn btn-danger">Thử lại</a>
                    <a href="/contact" class="btn btn-outline-secondary">Liên hệ hỗ trợ</a>
                </div>
            </div>
        </div>
    </div>
@endsection