// var app = angular.module('resourceApp', [], function($interpolateProvider) {
//     $interpolateProvider.startSymbol('<%');
//     $interpolateProvider.endSymbol('%>');
// });
//
//
// app.controller('resourceController', function($scope, $window) {
//     $scope.loadImageDescription = function(imageId, imageDescription) {
//         console.log("imageId: "+imageId);
//         console.log("imageDes: "+imageDescription);
//         $scope.imageId = imageId;
//         $scope.imageDescription = imageDescription;
//     };
//
//     // $scope.confirmDelete = function ($imageId) {
//     //     if (!$window.confirm("Are you sure you want to delete?")) {
//     //         return '';
//     //     };
//     // };
//
//     $scope.updateImageDescription = function() {
//         var imageId = $scope.imageId;
//         console.log(imageId);
//         console.log($scope.imageDescription);
//       $http.post('/api/updateImage',
//             {'id':imageId, 'description': $scope.imageDescription})
//           .success(function(response){
//               var id = response.id;
//               console.log(response.imageDes);
//               console.log(id);
//               $('#imageDesc_'+id).scope().imageDescription = response.imageDes;
//         });
//     };
// });

$('.collapse').collapse();