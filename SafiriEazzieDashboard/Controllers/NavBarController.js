NavBarController = {
    getNavBar : function (){
        xit.files.readJsonFile('Config/nav.config.json').then(function (content){
            var htmlContent = ''
            htmlContent += '<li class="nav-item">'
                htmlContent += '<a class="nav-link" onclick="NavBarController.itemClick(this)" href="./">'
                    htmlContent += '<i class="menu-icon typcn typcn-document-text"></i>'
                    htmlContent += '<span class="menu-title">Dashboard</span>'
                htmlContent += '</a>'
            htmlContent += '</li>'
            content.forEach(navItemModel => {
                htmlContent += '<li class="nav-item">'
                    htmlContent += '<a class="nav-link" data-toggle="collapse" href="#ui-basic' + navItemModel.id + '" aria-expanded="false" aria-controls="' + navItemModel.id + '">'
                        htmlContent += '<i class="menu-icon typcn typcn-' + navItemModel.icon + '"></i>'
                        htmlContent += '<span class="menu-title">' + navItemModel.label + '</span>'
                        htmlContent += '<i class="menu-arrow"></i>'
                    htmlContent += '</a>'
                    htmlContent += '<div class="collapse" id="ui-basic' + navItemModel.id + '">'
                        htmlContent += '<ul class="nav flex-column sub-menu">'
                            navItemModel.subs.forEach(navSubItemModel => {
                                htmlContent += '<li class="nav-item">'
                                htmlContent += '<a class="nav-link" href="#' + navSubItemModel.selector + '" view="' + navSubItemModel.view + '" onclick="navClick(this, null)">' + navSubItemModel.label + '</a>'
                                htmlContent += '</li>'
                            })
                        htmlContent += '</ul>'
                    htmlContent += '</div>'
                htmlContent += '</li>'
            })
            $('#ulNavBar').append(htmlContent)
        }).catch(function (error){
            alert(error)
        })
    },
    itemClick : function (view){
        // $('#ulItems li a').each(function () {
        //     $(this).removeClass('nav-link active')
        //     $(this).addClass('nav-link')
        //     $(this).collapse("hide")
        // });
        // $(view).addClass('nav-link active')
    }
}