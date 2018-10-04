<?php
// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Analytics Data Sync">
    <meta name="author" content="Daniel Paz - Visto Marketing">
    <title>Google Analytics Api V4</title>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="vendor/datatables/datatables/media/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <style>#generate{ color: #fff; } .t-20{ margin-top:20px;}</style>
  </head>
  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Google Analytics Data</a>
    </nav>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
                <div class="t-20 clearfix"></div>
                <div class="form-group">
                    <label for="View Id">Código da View:</label>
                    <input type="text" required class="form-control" id="view_id" aria-describedby="view id" placeholder="Código da View">
                </div>
                <div class="form-group">
                    <label for="Start Date">Data de inicio:</label>
                    <input type="date" class="form-control" id="start_date" name="trip" value="2018-07-22" />
                    <!-- <input type="text" required class="form-control" data-mask="00/00/0000" id="start_date" aria-describedby="start date" placeholder="Data de início"> -->
                </div>
                <div class="form-group">
                    <label for="End Date">Data de fim:</label>
                    <input type="date" class="form-control" id="end_date" name="trip" value="2018-07-22" />
                    <!-- <input type="text" required class="form-control" data-mask="00/00/0000" id="end_date" aria-describedby="end date" placeholder="Data de fim"> -->
                </div>
                <hr>
                <a onclick="callAnalytics()" id="generate" class="btn btn-primary"><span>Gerar relatório</span></a>
          </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h2 id="title" class="h2">Dashboard</h2>
          </div>
          <div id="report">
            <table id="analytics-report" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Página</th>
                        <th>Visualizações de páginas</th>
                        <th>Visualizações de páginas únicas</th>
                        <th>Tempo médio na página</th>
                        <th>Entradas</th>
                        <th>Taxa de rejeição</th>
                        <th>Porcentagem de saída</th>
                        <th>Valor da página</th>
                    </tr>
                </thead>
                <tbody id="analytics-data"><!-- HERE SHOW THE REPORT USING AJAX --></tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
<script src="vendor/components/jquery/jquery.min.js"></script>
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">

    jQuery('#analytics-report').DataTable();

    function callAnalytics() {
        if($("#view_id").val()=="")
        { 
            alert("Campo View ID vazio"); 
        }
        if($("#start_date").val()=="")
        { 
            alert("Campo Data de inicio vazio"); 
        }
        if($("#start_date").val()=="")
        { 
            alert("Campo Data de fim vazio"); 
        }
        else
        {
            // GET AND FORMAT START DATE
            var start_date = jQuery("#start_date").val();

            // GET AND FORMAT END DATE
            var end_date = jQuery("#end_date").val();
            
            // GET VIEW ID
            view_id = jQuery("#view_id").val();

            jQuery.ajax({
                type: "GET",
                url: 'Analytics.php',
                data: {view_id, start_date, end_date},
                    success: function(data)
                    {
                        $("#analytics-data").html(data);
                    }
            });

            $("#title").html('Dashboard : '+view_id);
        }
    }
</script>