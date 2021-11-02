<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Templates</title>
    <style>
        :root {
            --main-color: hsl(180, 100%, 25%);
            --main-color-dark: hsl(180, 100%, 20%);
        }
        html, body {
            font-size: 18px;
            font-family: sans-serif;
        }
        html, body, aside, main, nav, ul, li,
        div, a {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            min-height: 100vh;
            display: grid;
            grid-template-areas: 'aside main';
            grid-template-columns: clamp(170px, 20vw, 320px) minmax(0, 1fr);
        }
        aside {
            grid-area: aside;
            background-color: var(--main-color);
            box-shadow: #000 0 0 8px 0;
        }
        nav ul {
            list-style: none;
        }
        nav a {
            display: block;
            width: 100%;
            text-decoration: none;
            padding: .8rem 0 .8rem 1rem;
            color: #fff;
            border-bottom: 1px solid #ccc;
        }
        nav li > ul a {
            padding-left: 2rem;
        }
        nav a:hover {
            background-color: #666;
        }
        main {
            grid-area: main;
        }
        footer {
            grid-area: footer;
        }
        output {
            display: block;
            font-family: monospace;
            white-space: pre;
            margin: 1rem 0;
            max-width: 100%;
            overflow-x: auto;
        }
        #content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }
        hr {
            border-style: solid;
            border-color: #dbdbdb;
            margin: 2rem 0;
        }
        #noInputs {
            font-size: .9rem;
            font-style: italic;
            color: #333;
        }
        #inputs {
            padding: 0;
            display: grid;
            grid-gap: 1rem;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }
        .input-item {
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }

        #buttons {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 1rem 0;
            padding: 1rem 0;
        }

        #messages {
            position: fixed;
            transition: all 400ms ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            bottom: 1rem;
            left: 0;
            right: 0;
            pointer-events: none;
        }

        .message {
            display: block;
            padding: .8rem 1.2rem;
            background-color: #27AE60;
            color: #ffffff;
            margin: .4rem 0;
            visibility: visible;
            transition: all 400ms ease-in-out;
            border: 1px solid #21a85a;
            border-radius: 5px;
            box-shadow: 1px 1px 7px -3px rgba(0, 0, 0, .6);
            animation: fade-in 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
            animation-fill-mode: forwards !important;
        }

        .hide {
            animation: fade-out 1s ease-out both;
        }
        .hidden {
            display: none;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes fade-out {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                pointer-events: none;
                visibility: hidden;
            }
        }

        input[type="text"] {
            font-size: .8rem;
            padding: .4rem .6rem;
        }
        .row {
            margin: 1rem 0;
        }
        textarea {
            resize: vertical;
            min-height: 4rem;
            height: calc(100vh - 22rem);
            padding: 1rem;
            font-size: 1rem;
        }

        .sub-edit {
            margin-left: .6rem;
        }

        .sub-edit a {
            font-size: .6rem;
        }

        .subtext {
            margin: 0;
            padding: 0;
            font-size: .8rem;
            font-style: italic;
        }

        .input-item label {
            font-size: .8rem;
        }

        button {
            appearance: none;
            background-color: var(--main-color);
            color: #fff;
            padding: .6rem .8rem;
            border-radius: .4rem;
            font-size: 1rem;
            border: 1px solid var(--main-color-dark);
            transition: background-color 400ms ease-in;
        }
        button:hover {
            background-color: var(--main-color-dark);
        }
    </style>
</head>
<body>
<aside>
    <nav>
        <ul>
            <li>
                <a href="/">Templates</a>
                <ul id="template-nav"></ul>
            </li>
            <li><a href="/template/create">Create a template</a></li>
        </ul>
    </nav>
</aside>
<main>
    <div id="content">
