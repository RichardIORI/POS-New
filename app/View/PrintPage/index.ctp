<header>
    <?php echo $this->element('navbar'); ?>
</header>

<div class="container" ng-app="app" ng-controller="printPageCtrl">

    <button class="btn btn-info" type="button" name="button">Insert Category</button>
    <select class="" name="" ng-model="selectedType" ng-options="type for type in types">
    </select>

    <button class="btn btn-info" type="button" name="button" ng-click="insertLine(selectedType)">Insert Line</button>


    <!-- line details -->
    <div class="" ng-repeat="(key, value) in data | filter: typeFilter(selectedType)">
        <div class="col-sm-8">
            <label for="">Content</label>
            <input class="form-control" type="text" name="" ng-model="value.content">
        </div>
        <div class="col-sm-1">
            <label for="">Offset X</label>
            <input class="form-control" type="text" name="" ng-model="value.offset_x">
        </div>
        <div class="col-sm-1">
            <label for="">Index</label>
            <input class="form-control" type="text" name="" ng-model="value.line_index">
        </div>
        <div class="col-sm-2">
            <button class="btn btn-info" type="button" name="button" ng-click="updateLine('receipt-header', value.content, value.offset_x, value.line_index)">Update</button>
            <button class="btn btn-danger" type="button" name="button" ng-click="deleteLine('receipt-header', value.line_index)">Delete</button>
        </div>

    </div>

</div>




<?php echo $this->Html->script(array('lib/angular.min.js', 'lib/lodash.min.js', 'lib/angular-filter.min.js','angular/app.js', 'angular/controllers/printPage.js' )); ?>
