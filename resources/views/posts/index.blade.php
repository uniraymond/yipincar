@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Posts</div>

                    <div class="panel-body">
                        <div class="col-md-2">Post ID</div>
                        <div class="col-md-10">Post Title</div>
                        @foreach ($jsonPost as $allArticles)
                            <div class="col-md-1">{{$allArticles['id']}}</div>
                            <div class="col-md-11"><a href='post/{{$allArticles["id"]}}'>{{$allArticles['title']}}</a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var app = angular.module('myApp', []);

        app.controller('PeopleController', function($http, $scope){
            $scope.pageNumbers = [],
                    $scope.currentPage = 1,
                    $scope.numPerPage = 10,
                    $scope.getPeople = function(currentPage, searchName) {
                        $scope.nameUrl = 'api/list'; //can't load the site directly
                        $scope.currentPage = currentPage;
                        if (searchName) {
                            $http.get($scope.nameUrl).success(function(data){
                                $scope.people = data;
                            });
                        } else {
                            $http.get($scope.nameUrl).success(function(data){
                                $scope.people = [];
                                $scope.maxSize = 5;
                                var beginningData = ($scope.currentPage-1)*$scope.numPerPage;
                                for(var i= beginningData; i<beginningData + $scope.numPerPage; i++) {
                                    $scope.people.push(data[i]);
                                }
                                $scope.totalItems = data.length;
                                $scope.maxPage = Math.ceil(data.length / $scope.numPerPage);

                                if($scope.currentPage >= 1) {
                                    $scope.pageNumbers.splice(0,$scope.pageNumbers.length);
                                }
                                for(var j=$scope.currentPage; j<=$scope.currentPage + $scope.maxSize; j++) {
                                    $scope.pageNumbers.push(j);
                                }

                                if($scope.currentPage == 1) {
                                    $scope.hasPre = true;
                                    $scope.hasNext = false;
                                } else if($scope.currentPage == $scope.maxPage) {
                                    $scope.hasNext = true;
                                    $scope.hasPre = false;
                                } else {
                                    $scope.hasNext = false;
                                    $scope.hasPre = false;
                                }

                            });
                        }

                    }

            //caculate signin each year.
            var countYearLogin = function(data) {
                var count = [];
                var date2010 = date2011 = date2012 = date2013 = date2014 = date2015 = others = 0;
                for(var l=0; l<data.length; l++) {
                    var d = new Date(data[l].joindate);
                    switch(d.getFullYear()) {
                        case 2010: date2010++;
                            break;
                        case 2011: date2011++;
                            break;
                        case 2012: date2012++;
                            break;
                        case 2013: date2013++;
                            break;
                        case 2014: date2014++;
                            break;
                        case 2015: date2015++;
                            break;
                        default: others++;
                            break;
                    }
                }
            }

            //load the first page
            $scope.getPeople(1, '');

            //search data
            $scope.$watch('searchInput', function(newData,oldData){
                $scope.getPeople(1, newData);
            });

            //click page number
            $scope.changePage = function(pageNumber){
                $scope.currentPage = pageNumber;
                $scope.getPeople($scope.currentPage, '');
            }

            //next page
            $scope.nextPage = function(){
                console.log('cu'+$scope.currentPage);
                if ($scope.currentPage < $scope.maxPage) {
                    $scope.currentPage++;
                    $scope.hasPre = false;
                } else {
                    $scope.hasNext = true;
                }
                $scope.getPeople($scope.currentPage, '');
            }

            //pre page
            $scope.prePage = function(){
                if ($scope.currentPage > 1) {
                    $scope.currentPage--;
                    $scope.hasNext = false;
                } else {
                    $scope.hasPre = true;
                }
                console.log($scope.currentPage);
                $scope.getPeople($scope.currentPage, '');
            }
        });
    </script>
@endsection
