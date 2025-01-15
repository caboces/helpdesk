<!-- Labor rates modal -->
<div class="modal fade" id="labor-rates-modal" tabindex="-1" aria-labelledby="labor-rates-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="labor-rates-modal-label">Labor Rates</h1>
            </div>
            <div class="modal-body">
            <?php

                use kartik\grid\GridView;

                echo GridView::widget([
                    'dataProvider' => $laborRatesDataProvider,
                    'columns' => $laborRatesColumns,
                ]);
            ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>