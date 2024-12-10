addEventListener("DOMContentLoaded", (event) => {
    displayPlays(apiUrlCall);
});

// Function to display plays
displayPlays = (apiUrlCall) => {
    let conjureContainer= document.getElementById('conjure_container');
    let id = 'plays_menu';

    // Fetching shakespear plays
    fetch(apiUrlCall)
    .then(handshakeResponse => {
        return handshakeResponse.json();
    })
    .then(jsonResponse => {
        let playMenuData = [];

        // Populates associative array of plays
        jsonResponse.forEach((play) => {
            playMenuData[play.work_id] = `${play.work_title} [${play.work_genre}]`; //https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals
        })

        // Conjures and adds the plays menu to the page
        let playMenu = conjure_menu(playMenuData, id, 'Choose Play', sceneCallback);
        playMenu.setAttribute("api-url", apiUrlCall); // Work around global url variable
        conjureContainer.appendChild(playMenu);
    }).catch(err => console.error(err));
}

// Function to display scenes
sceneCallback = (e) => {
    let conjureContainer= document.getElementById('conjure_container');
    let id = 'scene_menu';

    // Removes existing scene paragraphs (if a person chooses a different play)
    let sceneParagraphs = document.getElementById("scene_wrapper");
    if (sceneParagraphs) {
        sceneParagraphs.remove();
    }
    
    // Removes existing scene menu (if a person chooses a different play)
    let sceneMenu = document.getElementById(id);
    if (sceneMenu) {
        sceneMenu.remove();
    }

    // Makes an AJAX request if an actual play is selected
    let selectMenu = e.target;
    let playId = selectMenu.options[selectMenu.selectedIndex].value;
    if(playId) {
        // Creates appropriate API url
        let apiUrlCall = `${selectMenu.getAttribute('api-url')}&work=${playId}`;

        // Fetching scenes
        fetch(apiUrlCall)
        .then(handshakeResponse => {
            return handshakeResponse.json();
        })
        .then(jsonResponse => {
            let sceneMenuData = [];

            // Populates associative array of scenes
            jsonResponse.forEach((scene) => {
                sceneMenuData[`${scene.scene_act}:${scene.scene_scene}`] = `Act ${scene.scene_act} : Scene ${scene.scene_scene} - ${scene.scene_location}`;
            })

            // Conjures and adds the scene menu to the page
            let sceneMenu = conjure_menu(sceneMenuData, id, 'Choose a Scene', paragraphCallback);
            sceneMenu.setAttribute("api-url", apiUrlCall);
            conjureContainer.appendChild(sceneMenu);
        }).catch(err => console.error(err));
    }
}

paragraphCallback = (e) => {
    let conjureContainer= document.getElementById('conjure_container');
    let id = 'scene_wrapper';
    
    // Removes existing scene paragraphs (if a person chooses a different scene)
    let sceneParagraphs = document.getElementById(id);
    if (sceneParagraphs) {
        sceneParagraphs.remove();
    }

     // Makes an AJAX request if an actual scene is selected
    let selectMenu = e.target;
    let actAndScene = selectMenu.options[selectMenu.selectedIndex].value;
    if(actAndScene) {
        // Creates appropriate API url
        let act = actAndScene.substring(0, actAndScene.indexOf(":"));
        let scene = actAndScene.substring(actAndScene.indexOf(":")+1);
        let apiUrlCall = `${selectMenu.getAttribute('api-url')}&act=${act}&scene=${scene}`;

        console.log(apiUrlCall);
        // Fetching paragraphs
        fetch(apiUrlCall)
        .then(handshakeResponse => {
            return handshakeResponse.json();
        })
        .then(jsonResponse => {
            // Creates scene paragraphs wrapper
            sceneParagraphs = document.createElement("div");
            sceneParagraphs.setAttribute("id",id);

            // Populates the wrapper
            jsonResponse.paragraphs.forEach(p => {
                let paragraph = document.createElement("p")
                paragraph.innerHTML = `[${p[1]}] ${p[2]}`;
                sceneParagraphs.appendChild(paragraph);
            });

            //Conjures the wrapper
            conjureContainer.appendChild(sceneParagraphs);
        }).catch(err => console.error(err));
    }
}

// Menu conjuring function
function conjure_menu(menu_options, menu_id, dummy_text = '', onchange_callback_function = null) {

    let select_menu = document.createElement("select");
    select_menu.setAttribute("id",menu_id);
    select_menu.setAttribute("name",menu_id);

    let option = null;   // to reuse for all the options to create

    // possibly  set the dummy option
    if (dummy_text != '') {
        option = document.createElement("option")
        option.setAttribute("value",'');
        option.textContent = dummy_text;

        select_menu.appendChild(option);
    }

    //https://masteringjs.io/tutorials/fundamentals/foreach-object
    Object.entries(menu_options).forEach(([value, text]) => {
        option = document.createElement("option");
        option.setAttribute("value",value);
        option.textContent = text;

        select_menu.appendChild(option);
    });

    // Finally set the event handler callback, if called for
    if ( typeof(onchange_callback_function) == 'function' ) {
        select_menu.onchange = onchange_callback_function;
    }

    return select_menu;
}