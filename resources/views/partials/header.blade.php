<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Studio Musik ITERA</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/img/logo-itera.png') }}" type="png" />

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/iconly.css') }}" />

    {{-- dataTable --}}
    <link rel="stylesheet" href="{{ asset('assets/dataTable/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dataTable/table-datatable-jquery.css') }}" />
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> --}}

    {{-- DatePicker --}}
    <link rel="stylesheet" href="{{ asset('assets/flatpickr/flatpickr.min.css') }}">

    {{-- Quill --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/quill/quill.snow.css') }}" /> --}}


    <script src="{{ asset('assets/js/initTheme.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/error-403.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/error.css') }}">

    {{-- Choices --}}
    <link rel="stylesheet" href="{{ asset('assets/choices/choices.css') }}">

    {{-- MIDTRANS --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <style>
        .loader-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background-color: rgba(255, 255, 255, 0.5); */
            /* Background overlay */
            z-index: 9999;
        }

        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: start
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 2em;
            cursor: pointer;
        }

        .rating label:hover,
        .rating label:hover~label {
            color: orange;
        }

        .rating input:checked~label {
            color: orange;
        }
    </style>

</head>
