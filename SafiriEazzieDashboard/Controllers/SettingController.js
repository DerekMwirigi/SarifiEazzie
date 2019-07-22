SettingController = {
    createVehicleMake : function (view, makeModel){
        $(view).text('Creating...')
        xit.request.post(['Authorization:Bearer ' + AuthController.getToken()], makeModel, endpoints.common.create_vehicle_make).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                $('#tMake').val()
                SettingController.displayVMakesListView(response.data)
            }else { 
                Observer.displayErrors(response)
            }
            $(view).text('Create')
        }).catch(function (error){
            $(view).text('Create')
            alert(error)
        })
    },
    getVehicleMakes : function (){
        Observer.status(Observer.states.LOADING, '#tVMakes')
        xit.request.get(null, null, endpoints.common.fetch_vehicle_makes).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                SettingController.displayVMakesListView(response.data)
            }else{
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            console.log(error)
        }) 
    },
    displayVMakesListView :function (makeModels){
        var htmlContent = ''
        makeModels.forEach(makeModel => {
            htmlContent += '<tr>'
            htmlContent += '<td>' + makeModel.id + '</td>'
            htmlContent += '<td>' + makeModel.make + '</td>'
            htmlContent += '<td>' + makeModel.models + '</td>'
            htmlContent += '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>'
            htmlContent += '</tr>'
        })
        $('#tVMakes').html(htmlContent)
    },
    getModels : function (){
        
    },
    createRoute : function (view, routeModel){
        $(view).text('Creating...')
        xit.request.post(['Authorization:Bearer ' + AuthController.getToken()], routeModel, endpoints.common.create_route).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                $('#tRoute').val()
                $('#tPeakFare').val()
                $('#tOffPeakFare').val()
                SettingController.displayRoutesListView(response.data)
            }else { 
                Observer.displayErrors(response)
            }
            $(view).text('Create')
        }).catch(function (error){
            $(view).text('Create')
            alert(error)
        })
    },
    getRoutes : function (){
        Observer.status(Observer.states.LOADING, '#tbRoutes')
        xit.request.get(null, null, endpoints.common.fetch_routes).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                SettingController.displayRoutesListView(response.data)
            }else{
                Observer.displayErrors(response)
            }
        }).catch(function (error){
            console.log(error)
        }) 
    },
    displayRoutesListView :function (routeModels){
        var htmlContent = ''
        routeModels.forEach(routeModel => {
            htmlContent += '<tr>'
            htmlContent += '<td>' + routeModel.id + '</td>'
            htmlContent += '<td>' + routeModel.route + '</td>'
            htmlContent += '<td>' + routeModel.peakFare + '</td>'
            htmlContent += '<td>' + routeModel.offPeakFare + '</td>'
            htmlContent += '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>'
            htmlContent += '</tr>'
        })
        $('#tbRoutes').html(htmlContent)
    }
}