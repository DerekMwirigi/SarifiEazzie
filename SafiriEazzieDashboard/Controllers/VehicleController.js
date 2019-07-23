VehicleController = {
    create : function (view, vehicleModel){
        $(view).text('Creating...')
        xit.request.post(null, vehicleModel, endpoints.vehicle.create).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                $('#myModal').modal('hide')
                xit.storage.saveItem('vehicleModels', JSON.stringify(response.data))
                xit.ui.openview('GET', null, null, 'Views/Vehicle/listview.html', '#panelVehicles', false)
            }else { 
                Observer.displayErrors(response)
            }
            $(view).text('Create')
        }).catch(function (error){
            $(view).text('Create')
            alert(error)
        })
    },
    getVehicles : function (){
        Observer.status(Observer.states.LOADING, '#panelVehicles')
        $('#filters').fadeOut('fast')
        xit.request.get(null, null, endpoints.vehicle.fetch).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                xit.storage.saveItem('vehicleModels', JSON.stringify(response.data))
                xit.ui.openview('GET', null, null, 'Views/Vehicle/listview.html', '#panelVehicles', false)
                if(response.pagination != null){
                    xit.storage.saveItem('pagination', JSON.stringify(response.pagination))
                    $('#filters').fadeIn('fast')
                    $('#spRange').html(response.pagination.range)
                }
            }else{
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            console.log(error)
        })
    },
    displayVehiclesListView :function (vehicleModels){
        var htmlContent = ''
        vehicleModels.forEach(vehicleModel => {
            console.log(vehicleModel)
            htmlContent += '<tr>'
            htmlContent += '<td>' + vehicleModel.id + '</td>'
            htmlContent += '<td>' + vehicleModel.regNumber + '</td>'
            htmlContent += '<td>' + vehicleModel.ownerFirstName + '</td>'
            htmlContent += '<td>' + vehicleModel.make + '<em> :: </em>' + vehicleModel.model + '</td>'
            htmlContent += '<td>' + vehicleModel.datePurchase + '</td>'
            htmlContent += '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>'
            htmlContent += '</tr>'
        })
        $('#tbVehicles').html(htmlContent)
    },
    loadOwners : function (){
        xit.request.get(null, null, endpoints.common.fetch_owners).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                response.data.forEach(ownerModel => {
                    $('#dOwner').append('<option value="'+ownerModel.id+'">'+ownerModel.firstName+'</option>')
                })
            }else{
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            console.log(error)
        })
    },
    loadMakes : function (){
        xit.request.get(null, null, endpoints.common.fetch_vehicle_makes).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                response.data.forEach(vMake => {
                    $('#dMake').append('<option value="'+vMake.id+'">'+vMake.make+'</option>')
                })
            }else{
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            console.log(error)
        })
    },
    loadModels : function (makeId){
        $('#dModel').html('')
        xit.request.get(null, null, endpoints.common.fetch_vehicle_models+'?makeId='+makeId).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                response.data.forEach(vModel => {
                    $('#dModel').append('<option value="'+vModel.id+'">'+vModel.model+'</option>')
                })
            }else{
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            console.log(error)
        })
    }
}