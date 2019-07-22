DriverController = {
    create : function (view, driverModel){
        $(view).text('Creating...')
        xit.request.post(null, driverModel, endpoints.driver.create).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                $('#myModal').modal('hide')
                xit.storage.saveItem('driverModels', JSON.stringify(response.data))
                xit.ui.openview('GET', null, null, 'Views/Driver/listview.html', '#panelDrivers', false)
            }else { 
                Observer.displayErrors(response)
            }
            $(view).text('Create')
        }).catch(function (error){
            $(view).text('Create')
            alert(error)
        })
    },
    getDrivers : function (){
        Observer.status(Observer.states.LOADING, '#panelDrivers')
        $('#filters').fadeOut('fast')
        xit.request.get(null, null, endpoints.driver.fetch).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                xit.storage.saveItem('driverModels', JSON.stringify(response.data))
                xit.ui.openview('GET', null, null, 'Views/Driver/listview.html', '#panelDrivers', false)
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
    displayDriversListView :function (driverModels){
        var htmlContent = ''
        driverModels.forEach(driverModel => {
            htmlContent += '<tr>'
            htmlContent += '<td>' + driverModel.id + '</td>'
            htmlContent += '<td>' + driverModel.firstName + ' ' + driverModel.lastName + '</td>'
            htmlContent += '<td>' + driverModel.idNo + '</td>'
            htmlContent += '<td>' + driverModel.mobile + '</td>'
            htmlContent += '<td>' + driverModel.email + '</td>'
            htmlContent += '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>'
            htmlContent += '</tr>'
        })
        $('#tbDrivers').html(htmlContent)
    }
}