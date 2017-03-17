app.controller('printPageCtrl', ['$scope', '$http',
    function($scope, $http) {
        init()

        function init() {
            $http.get('js/angular/data/print.json').then(function(response) {
                $scope.data = response.data
                console.log($scope.data)
                $scope.types = _.values(_.mapValues(_.uniqBy($scope.data, 'type'), (item)=>{ return item.type }));
                $scope.selectedType = $scope.types[0]
            })
        }

        $scope.insertLine = function(type) {
            var empty_data = {
                                "type": type,
                                "content": "",
                                "offset_x": 0,
                                "line_index" : $scope.data.length,
                                "lang_code": "en",
                                "bold": false
                            }
            $scope.data.push(empty_data)
            console.log($scope.data)
        }

        $scope.updateLine = function(type, content, line_index, offset_x) {
            // update backend with ajax

            console.log($scope.data)
        }
        $scope.deleteLine = function (type, line_index) {
            $scope.data.forEach(function(value, index) {
                if (value.line_index == line_index) {
                    $scope.data.splice(index, 1)
                }
            })
            // reorder the data
            // _.sortBy($scope.data, "line_index")
            $scope.data.forEach( function(value, index) {
                value.line_index = index
            })

            //  send ajax to backend to update data

            console.log("delete")
            console.log($scope.data)
            // after delete line, if the deleted is not the last one, reorder the lines
        }

        $scope.typeFilter = function(type) {
            return function(item) {
                return item.type == type
            }
        }

    }
])
