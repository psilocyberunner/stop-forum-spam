<?php

if (!empty($_GET['src'])) {

    switch ($_GET['src']) {
        case 'by-email':
            highlight_file('SearchByEmail.php');
            break;

        case 'by-email-hash':
            highlight_file('SearchByEmailHash.php');
            break;

        case 'by-domain':
            highlight_file('SearchByDomain.php');
            break;

        case 'by-ip':
            highlight_file('SearchByIp.php');
            break;

        case 'by-username':
            highlight_file('SearchByUsername.php');
            break;

        case 'search-multiple':
            highlight_file('SearchMultiple.php');
            break;
    }

    exit;
}

if (!empty($_GET['search'])) {

    switch ($_GET['search']) {
        case 'by-email':
            $result = require_once 'SearchByEmail.php';
            break;

        case 'by-email-hash':
            $result = require_once 'SearchByEmailHash.php';
            break;

        case 'by-domain':
            $result = require_once 'SearchByDomain.php';
            break;

        case 'by-ip':
            $result = require_once 'SearchByIp.php';
            break;

        case 'by-username':
            $result = require_once 'SearchByUsername.php';
            break;

        case 'search-multiple':
            $result = require_once 'SearchMultiple.php';
            break;
    }

    header('Content-Type: application/json; charset=UTF-8');

    echo $result;

    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="https://www.stopforumspam.com/favicon.ico">

    <title>Stop forum spam examples</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="javascript:window.location.reload()">S.F.S</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="javascript:getSearchSrc('by-email'); getSearchResults('by-email');">Search by email</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:getSearchSrc('by-email-hash'); getSearchResults('by-email-hash');">Search by hash</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:getSearchSrc('by-domain'); getSearchResults('by-domain');">Search by domain</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:getSearchSrc('by-ip'); getSearchResults('by-ip');">Search by IP</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:getSearchSrc('by-username'); getSearchResults('by-username');">Search by username</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:getSearchSrc('search-multiple'); getSearchResults('search-multiple');">Search multiple items</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 150px;">

    <div class="row">
        <div class="col-lg-12">
            <h5 id="search-type">Choose one of desired search types.</h5>
            <div id="src" style="white-space: pre; font-family: monospace;"></div>
            <h5 id="search-results"></h5>
            <code id="result" style="white-space: pre; font-family: monospace;"></code>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
<script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>

<script>

    clearResults = function () {
        $('#src').html('');
        $('#search-type').html('');
        $('#search-results').html('Results');
    };

    getSearchSrc = function (type) {
        clearResults();
        $.ajax({
            url: window.location.href + '?src=' + type,
            success: function (result) {
                $('#src').html(result);
            }
        });
    };

    getSearchResults = function (type) {
        clearResults();
        $.ajax({
            url: window.location.href + '?search=' + type,
            success: function (result) {
                $('#search-type').html('Search ' + type);
                $('#result').html(JSON.stringify(result, null, 4));
            }
        });
    };
</script>
</body>
</html>
