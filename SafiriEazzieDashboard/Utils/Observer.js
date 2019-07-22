Observer = {
    states: {
        LOADING: 'LOADING',
        DONE: 'DONE'
    },
    status : function (state, view){
        if(state == 'LOADING'){
            $(view).html(xit.ui.processPlaceHolder);
            //$(view).html('Loading, please wait...')
        }else{
            $(view).html()
        }
    },
    displayErrors : function (response){
        var errors = '\n'+response.message+'\n'
        response.errors.forEach(error => {
            errors+=error+'\n'
        });
        alert('Errors' + errors)
    }
}