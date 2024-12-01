<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Form Editors')); ?>

        <!-- quill css -->
        <link href="../core/assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
        <link href="../core/assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />

        <?php include '../core/partials/head-css.php'; ?>


<?php include '../core/partials/body.php'; ?>

    <!-- Begin page -->
    <div id="layout-wrapper">

    <?php include '../core/partials/menu.php'; ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Forms' , 'title' => 'Form Editors')); ?>

  <link rel="stylesheet" href="../core/assets/editor/css/froala_editor.css">
  <link rel="stylesheet" href="../core/assets/editor/css/froala_style.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/code_view.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/draggable.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/colors.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/emoticons.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/image_manager.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/image.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/line_breaker.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/table.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/char_counter.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/video.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/fullscreen.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/file.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/quick_insert.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/help.css">
  <link rel="stylesheet" href="../core/assets/editor/css/third_party/spell_checker.css">
  <link rel="stylesheet" href="../core/assets/editor/css/plugins/special_characters.css">

  <style>
    body {

    }

    div#editor {
      width: 100%;
      margin: auto;
      text-align: left;
    }

    .ss {
      background-color: red;
    }
  </style>
</head>

<body>
  <div id="editor">
    <div id='edit' style="margin-top: 30px;">


    </div>

  </div>

  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js"></script>

  <script type="text/javascript" src="../core/assets/editor/js/froala_editor.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/emoticons.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/file.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/fullscreen.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/image_manager.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/quick_insert.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/video.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/help.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/print.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/third_party/spell_checker.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/special_characters.min.js"></script>
  <script type="text/javascript" src="../core/assets/editor/js/plugins/word_paste.min.js"></script>

<script>
  (function () {
    new FroalaEditor("#edit", {
      attribution: false // This disables the "Powered by Froala" message
    });
  })();
</script>

        
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                
                <?php include '../core/partials/footer.php'; ?>

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <?php include '../core/partials/right-sidebar.php'; ?>

        <?php include '../core/partials/vendor-scripts.php'; ?>



        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
