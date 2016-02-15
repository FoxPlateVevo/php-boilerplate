<?php
require_once __PATH__ . "/app/service/MyNew.php";

//set service and resource data
$myNew = new Service_MyNew();
$newsResource = $myNew->news;

$this->respond("GET", "/?", function ($request, $response, $service) use ($newsResource) {
    //list all news
    $news = $newsResource->listNews();
    
    //header params
    $service->pageTitle = "App News";
    
    //content params
    $service->message = $request->param("m");
    $service->news = $news;
    
    //render
    $service->render(__PATH__ . "/app/view/news/list.phtml");
});

$this->respond("GET", "/create", function ($request, $response, $service) {
    //header params
    $service->pageTitle = "Create New";
    
    $service->render(__PATH__ . "/app/view/news/form.phtml");
});

$this->respond("GET", "/edit/[:id]", function ($request, $response, $service) use ($newsResource) {
    $id = $request->param("id");
    
    $news = $newsResource->listNews([
        "id" => $id
    ]);
    
    //header params
    $service->pageTitle = "Edit New";
    
    //content params
    $service->new = array_shift($news);
    
    $service->render(__PATH__ . "/app/view/news/form.phtml");
});


//methods
$this->respond("POST", "/new", function ($request, $response, $service) use ($newsResource) {
    $txtTitle       = $request->param("txtTitle");
    $txtDate        = $request->param("txtDate", date("Y-m-d"));
    $cboStatus      = $request->param("cboStatus");
    $txtDescription = $request->param("txtDescription");
    
    $success = $newsResource->insert(new _New([
        "title"         => $txtTitle,
        "description"   => $txtDescription,
        "dateCreate"    => $txtDate,
        "status"        => $cboStatus
    ]));
    
    if($success){
        $response->redirect("/news?m=created");
    }else{
        $response->redirect("/news?m=no_created");
    }
});

$this->respond("POST", "/update/[:id]", function ($request, $response, $service) use ($newsResource) {
    $id             = $request->param("id");
    $txtTitle       = $request->param("txtTitle");
    $txtDate        = $request->param("txtDate", date("Y-m-d"));
    $txtDescription = $request->param("txtDescription");
    
    $news = $newsResource->listNews([
        "id" => $id
    ]);
    
    $new = array_shift($news);
    
    $new->setTitle($txtTitle);
    $new->setDateCreate($txtDate);
    $new->setDescription($txtDescription);
    
    $success = $newsResource->update($new);
    
    if($success){
        $response->redirect("/news?m=updated");
    }else{
        $response->redirect("/news?m=no_updated");
    }
});

$this->respond("GET", "/delete/[:id]", function ($request, $response, $service) use ($newsResource) {
    $id = $request->param("id");
    
    $success = $newsResource->delete($id);
    
    if($success){
        $response->redirect("/news?m=deleted");
    }else{
        $response->redirect("/news?m=no_deleted");
    }
});