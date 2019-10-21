<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Fun CaG</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

  <link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/default.min.css">
  
  <style type='text/css'>
    pre, code {
        white-space: pre;
        color: black;
    }
  </style>
</head>

<body id="page-top">

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Fun CAG</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Files
      </div>

      <!-- Nav COG Item -->
      <form>
          @foreach($files as $file)
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" onclick="outputFile('{{$id}}','{{$file['name']}}','{{$file['path']}}');">
              <input type="checkbox" name="uploadscript" value="{{ $file['name'] }}_{{ $file['index'] }}">
              <span>{{ $file['name'] }}</span>
            </a>
          </li>
          @endforeach

        <!-- Divider -->
        <hr class="sidebar-divider  my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active" onclick="exportFile('{{$id}}')">
          <a class="nav-link" href="#">
            <span>Export Files</span></a>
        </li>
      </form>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="navbar-brand mr-1" href="{{ url('/filelist/'.$id) }}">Files</a>
            </li>
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="navbar-brand mr-1" href="{{ url('/analyze/'.$id) }}">Analyze</a>
            </li>
          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
  
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <!-- Breadcrumbs-->
            <ol id="headfile" class="breadcrumb">
            </ol>
          </div>

          <div class="row">
            <!-- Save path here -->
            <input type="hidden" id="pathId" name="pathId" value="">

            <!-- Area Code -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 id="mainfile" class="m-0 font-weight-bold text-primary"></h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" id="codeTables" width="100%" cellspacing="0">

                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Area Project -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Project Statistics</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTables" width="100%" cellspacing="0">
                        <tbody>
                          <tr>
                            <td>Language: </td>
                            <td id="statLang"></td>
                          </tr>
                          <tr>
                            <td>Files: </td>
                            <td id="statFiles"></td>
                          </tr>
                          <tr>
                            <td>Folders: </td>
                            <td id="statFolder"></td>
                          </tr>
                          <tr>
                            <td>Methods: </td>
                            <td id="statMeth"></td>
                          </tr>
                          <tr>
                            <td>Recursive Methods: </td>
                            <td id="statRec"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div>

          </div>

        </div>
      <!-- End of Main Content -->

    </div>
    <!-- /.content-wrapper -->
    
  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Code Snippet-->
            <div class="table-responsive">
              <table class="table" id="funTables" width="100%" cellspacing="0">

              </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Page level plugin JavaScript-->
  <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

  <!-- Demo scripts for this page-->
  <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/highlight.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/highlightjs-line-numbers.js@2.6.0/dist/highlightjs-line-numbers.min.js"></script>
  <script src="//cdn.jsdelivr.net/gh/TRSasasusu/highlightjs-highlight-lines.js@1.1.5/highlightjs-highlight-lines.min.js"></script>

  <script>
    hljs.initHighlightingOnLoad();

    $("input[type=checkbox]").prop('checked', false);

    function outputFile(id, filename, fileindex){
        // Handle Breadcrumb Item
        $('#headfile > li').remove();

        // Clear previous value
        $("#codeTables tr").remove(); 
        document.getElementById("mainfile").innerHTML = "";

        // Insert the header
        document.getElementById("mainfile").innerHTML = filename;
        document.getElementById("pathId").value = fileindex;
        codeStatistics(id, filename, fileindex);

        // Handle the file path
        var filePath = fileindex.split(filename);
        filePath = filePath[0].split(id);
        console.log(filePath);

        $.get('/filelist/'+id+'/'+filename+'/'+filePath[1], function(response) {
            const linecoder = response['content'].split("[EOF]");

            // Find a <table> element with id="myTable":
            const table = document.getElementById("codeTables");

            // Copy the code
            linecoder[1] = linecoder[0];

            var regexline = linecoder[0].match(/[^\r\n]+/gm);
            const stateRegex = purgeList(linecoder[0]);
            const metRegex = metList(linecoder[0]);
            for(x in regexline) {
              // Add Current Item
              const div = document.createElement('li');
              const metlink = "<a data-toggle='modal' class='openDialog' data-id='"
                          +stateRegex[x]+"' data-target='#codeModal'><font color='blue'>"
                          +metRegex[x]+"</font></a>";               
              div.className = 'breadcrumb-item';
              var cleanRegex = regexline[x]
                                .replace(stateRegex[x], "")
                                .replace(":","")
                                .replace(metRegex[x], metlink);
              div.innerHTML = cleanRegex;
              document.getElementById('headfile').appendChild(div);
            }
            
            // Handle Codeline
            var contentLine = linecoder[1].match(/[^\r\n]+/gm);
            let y = 1;
            for(x in contentLine) {
              const link = "<a data-toggle='modal' class='openDialog' data-id='"
                          +stateRegex[x]+"' data-target='#codeModal'><font color='blue'>"
                          +stateRegex[x]+"</font></a>"; 
              contentLine[x] = contentLine[x].replace(stateRegex[x], "").replace(":", ""); 
              stateRegex[x] = link + "\n";
              contentLine[x] = contentLine[x] + "\n";  
              
              // Create an empty <tr> element and add it to the 1st position of the table:
              var row = table.insertRow();

              // Insert new cells (<td> elements) at the 1st and 3rd position of the "new" <tr> element:
              var cell1 = row.insertCell(0);
              var cell2 = row.insertCell(1);
              var cell3 = row.insertCell(2);

              // Add some text to the new cells:
              cell1.innerHTML = y;
              cell2.innerHTML = "<code>"+contentLine[x]+"</code>";            
              cell3.innerHTML = stateRegex[x];

              y += 1;                                  
            }
            // $("#codeblocks").html(contentLine);
            // $("#funblocks").html(stateRegex);            
            // $(document).ready(function() {
            //   $('code.hljs').each(function(i, block) {
            //       hljs.lineNumbersBlock(block);
            //   });
            // });
          });
    }

    function metList(text) {
      return text.match(/([a-zA-Z0-9]+(?:_[a-zA-Z0-9]+)*)\(.*?\)/gm);      
      // return text.match(/([a-zA-Z]+(?:_[a-zA-Z]+)*)\(.*?\)/gm);
      // return reglist.reduce(function(a,b){
      //         if (a.indexOf(b) < 0 ) a.push(b);
      //         return a;
      //       },[]);
    }

    function purgeList(text) {
      var reglist = text.match(/\(.*?\)/gm);
      return reglist.filter(s=>~s.indexOf(":"));
    }

    function funList(text) {
      var reglist = text.match(/\(.*?\)/g);
      reglist = reglist.filter(s=>~s.indexOf(":"));
      return reglist.reduce(function(a,b){
              if (a.indexOf(b) < 0 ) a.push(b);
              return a;
            },[]);
    }

    $(document).on("click", ".openDialog", function () {
        var temp = $(this).data('id');
        temp = temp.replace("(","").replace(")","").split(":");        

        // $("html, body").scrollTop($('#codejam').position().top);         

        // Clear previous value
        $("#funTables tr").remove(); 
        $("#exampleModalLongTitle").html("");

        $.get('/find/'+{{ $id }}+'/'+temp[0], function(response) {
          $("#exampleModalLongTitle").html(temp[0]);

          let endline = getProcedure(response, temp[1]-1);
          let codeline = "";

          // Find a <table> element with id="myTable":
          const table = document.getElementById("funTables");

          for (let i = temp[1]-1; i <= endline; i++) {
            const cb = response[i] + "\n";
            // codeline = codeline.concat(temp);            

            // Create an empty <tr> element and add it to the 1st position of the table:
            var row = table.insertRow();
            if((i == temp[1]-1)){
                row.id = 'anchor';
            } else if ((i>= temp[1]) && (i<=endline)) {
                row.id = 'deck-'+i;
            } 

            // Insert new cells (<td> elements) at the 1st and 3rd position of the "new" <tr> element:
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);

            // Add some text to the new cells:
            cell1.innerHTML = i+1;
            cell2.innerHTML = "<code>"+cb+"</code>";

            // Add Highlight
            $("#anchor").css('background','#3232');
            $("#deck-"+i).css('background','#3232');
          }

          // $("#codeblocks").html(codeline);
          
          // hljs.initHighlightLinesOnLoad([
          //     [{start: temp[1]-1, end: endline-1, color: '#fff'}], // Highlight line code
          // ]);

          $('#codeModal').animate({scrollTop: 
            document.querySelector('#anchor').offsetTop // X
          }, 3000);   
        });
    })

    function getProcedure(method,start) {
      // Use counter to count bracket
      let bracketCounter = 0;
      let ic = start;
      let posCounter = start;
      
      // First. check i or i+1 bracket position
      if(method[start+1].includes("{")) {
          bracketCounter++;
          ic = start + 1;
      } else if(method[start].includes("{")) {
          bracketCounter++;
      }

      // Do lexical analysis
      do {
        if(method[ic].includes("{")) {
          bracketCounter++;
        } else if (method[ic].includes("}")) {
          bracketCounter--;
          posCounter = ic;
        }
        ic++;
      } while (bracketCounter > 1);

      return posCounter;
    }

    function exportFile(id) {
      var uploadScript = document.forms[0];
      var txt = "";
      var i;
      for (i = 0; i < uploadScript.length; i++) {
        if (uploadScript[i].checked) {
            let fileName = uploadScript[i].value.split("_");
            // const link = document.createElement('a');
            // link.href = '/filelist/'+id+'/'+fileName[0]+'/'+fileName[1];
            // link.download = fileName[0];
            // link.download.click();
            var theAnchor = $('<a />')
              .attr('href', '/filelist/'+id+'/'+fileName[0]+'/'+fileName[1])
              .attr('download',fileName[0])
              // Firefox does not fires click if the link is outside
              // the DOM
              .appendTo('body');
          
          theAnchor[0].click(); 
          theAnchor.remove();            
        }
      }      
    }

    function codeStatistics(id, filename, fileindex) {
      // Clear Item
      $("#statLang").html("");
      $("#statFiles").html("");
      $("#statFolder").html("");

      // Add Item
      $("#statLang").html("{{ $csv[0]['language'] }}");
      $("#statFiles").html("{{ $code['total_files'] }}");
      $("#statFolder").html("{{ $code['total_folder'] }}");
      countRegex(id, filename, fileindex);
    }    

    function countRegex(id, filename, fileindex) {
      // Clear Item
      $("#statMeth").html("");
      $("#statRec").html("");      

      // Add Item      
      $.get('/filelist/'+id+'/'+filename+'/'+fileindex, function(response) {
        $("#statMeth").html(response.split("*").length-1);
        $("#statRec").html(response.split("(R)").length-1);            
      });
    }
  </script>
</body>

</html>