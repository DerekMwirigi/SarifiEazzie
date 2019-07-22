AuthController = {
    sessionState : function (){
        $('#dvContainer').html()
        if(JSON.parse(xit.storage.getValue('loggedInUser')) == null){
            xit.ui.openview('POST', null, null, 'Views/Auth/signIn.html', '#dvContainer', true)
        }else{
            xit.ui.openview('POST', null, null, 'Views/App/Index.html', '#dvContainer', true)
        }
    },
    signIn : function (view, authModel){
        $(view).text('Loading...')
        xit.request.post(null, authModel, endpoints.auth.signIn).then(function (response){
            response = JSON.parse(response)
            if(response.status_code == 1){
                xit.storage.saveItem('loggedInUser', JSON.stringify(response.data))
                location.reload()
            }else { 
                Observer.displayErrors(response)
            }
            $(view).text('Sign In')
        }).catch(function (error){
            $(view).text('Sign In')
            alert(error)
        })
    },
    signOut : function (){
        if(confirm('About to sign out. Continue?')){
            xit.storage.destroy('loggedInUser')
            location.reload()
        }
        return
    },
    getToken : function (){
        if(JSON.parse(xit.storage.getValue('loggedInUser')) == null){
            return null
        }
        return JSON.parse(xit.storage.getValue('loggedInUser')).token
    }
}