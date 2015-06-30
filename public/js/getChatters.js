/**
 * Created by alain on 6/18/15.
 */
function getChatters($scope, $http) {
    $http.get('/online-users').
        success(function(data) {
            $scope.users = data._embedded.online_users;
        });
}