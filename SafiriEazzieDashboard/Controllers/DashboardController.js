DashboardController = {
    stats : function (){
        xit.request.get(null, null, endpoints.analysis.stats).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                xit.storage.saveItem('sysStats', JSON.stringify(response.data))
                $('#h3DriversCount').html(response.data.entityCounts.drivers)
                $('#h3OwnersCount').html(response.data.entityCounts.owners)
                $('#h3VehiclesCount').html(response.data.entityCounts.vehicles)
                $('#h3UsersCount').html(response.data.entityCounts.users)
            }else { 
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            alert(error)
        })
    }
}