<div class="page-content-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <h3 class="page-title">
                    Feed Konfiguration </h3>
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="/backend">
                            Home
                        </a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="#">
                            Feed &Uuml;bersicht
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php if (!empty($message)) : ?>
            <script type="text/javascript">
                var toasterMessage = "<?php echo str_replace(array("\r", "\n"), '', $message) ?>";
            </script>
        <?php endif; ?>
        <script type="text/javascript">
            var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
        </script>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">&Uuml;bersicht</div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover dataTable" id="feedtable">
                            <thead>
                            <tr role="row" class="head">
                                <th width="5%">UID</th>
                                <th>Anbieter</th>
                                <th>Feed URL</th>
                                <th width="10%">Sportart</th>
                                <th>Kategorie</th>
                                <th>Turnier</th>
                                <th>Team</th>
                                <th width="5%">Aktionen</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="uid">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="name">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="url">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="sportname">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="categoryname">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="tournamentname">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="teamname">
                                </td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <p>
                            <?= anchor('backend/feed/create', 'Feed hinzufügen',
                                array('class' => 'btn btn-small green')) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
