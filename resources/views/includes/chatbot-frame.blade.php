<!doctype html>
<html>
<head>
    <title>AI ChatBot Frame</title>
    <meta charset="UTF-8">

    <!-- Bootstrap Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- Styles Scripts -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabler-icons/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/simplebar/dist/simplebar.css') }}">

    <!-- Chatbot Scripts -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">
    <style>
            html, body {
                background-color: #fff;
                background-image: none;
            }
    </style>
</head>
<body>

<script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js'></script>

<!-- Auto URL href -->
<script>
var ready = true;

// set interval
setInterval(setready, 1000);
function setready() {
  ready = true;
}

$(document).on('DOMNodeInserted', "#messageArea", function() {
  if(ready == true)
  {
  setTimeout(replacelink, 200);
  ready = false;
  }
});

function replacelink() {
  $('#messageArea .msg p').each(function(){
      text = $(this).html();
      link = text.match(/(Link:)\s(\/[^<]*)/g);
      if(link)
      {
        $(this).html(' ');
        url = link.toString().substring(5);
        text = text.match(/(.*)(Link:)/g).toString().substring(0,4);
        $(this).empty();
        $(this).html('<a href="' + url + '" download>' + text + '</a>');
        $(this).addClass('linked');
      }
      else
      {
        $(this).addClass('notlinked');
      }
  });
}

</script>
</body>
</html>