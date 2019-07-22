OwnerController = {
    create : function (view, ownerModel){
        $(view).text('Creating...')
        xit.request.post(null, ownerModel, endpoints.owner.create).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                $('#myModal').modal('hide')
                xit.storage.saveItem('ownerModels', JSON.stringify(response.data))
                xit.ui.openview('GET', null, null, 'Views/Owner/listview.html', '#panelOwners', false)
            }else { 
                Observer.displayErrors(response)
            }
            $(view).text('Create')
        }).catch(function (error){
            $(view).text('Create')
            alert(error)
        })
    },
    getOwners : function (){
        Observer.status(Observer.states.LOADING, '#panelOwners')
        $('#filters').fadeOut('fast')
        xit.request.get(null, null, endpoints.owner.fetch).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                xit.storage.saveItem('ownerModels', JSON.stringify(response.data))
                xit.ui.openview('GET', null, null, 'Views/Owner/listview.html', '#panelOwners', false)
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
    displayOwnersListView :function (ownerModels){
        var htmlContent = ''
        ownerModels.forEach(ownerModel => {
            htmlContent += '<tr>'
            htmlContent += '<td>' + ownerModel.id + '</td>'
            htmlContent += '<td>' + ownerModel.firstName + ' ' + ownerModel.lastName + '</td>'
            htmlContent += '<td>' + ownerModel.idNo + '</td>'
            htmlContent += '<td>' + ownerModel.mobile + '</td>'
            htmlContent += '<td>' + ownerModel.email + '</td>'
            htmlContent += '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>'
            htmlContent += '</tr>'
        })
        $('#tbOwners').html(htmlContent)
    }
}