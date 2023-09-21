let name_datafield_id = "name_datafield";
let description_datafield_id = "description_datafield";
let temperature_datafield_id = "temperature_datafield";
let humidity_air_datafield_id = "humidity_air_datafield";
let moisture_soil_datafield_id = "moisture_soil_datafield";
let light_status_datafield_id = "light_status_datafield";
let ph_datafield_id = "ph_datafield";
let last_watering_datetime_datafield_id = "last_watering_datetime_datafield";
let cooler_status_datafield_id = "cooler_status_datafield";
let planting_datetime_datafield_id = "planting_datetime_datafield";

let state_overlay_container_id = "state_overlay_container";
let state_overlay_default_id = "state_overlay_default";
let state_overlay_light_id = "state_overlay_light";
let state_overlay_temperature_id = "state_overlay_temperature";
let state_overlay_water_id = "state_overlay_water";
let state_overlay_cooler_id = "state_overlay_cooler";

let overlay_light_status_datafield_id = "overlay_light_status_datafield";
let overlay_temperature_datafield_id = "overlay_temperature_datafield";
let overlay_water_volume_datafield_id = "overlay_water_volume_datafield";
let overlay_cooler_status_datafield_id = "overlay_cooler_status_datafield"

let state_overlay_cooler_btn_id = "state_overlay_cooler_btn";
let state_overlay_cooler_text_id = "state_overlay_cooler_text";

function InitializeMainPage(cube_data, report_data){
    ResetMainPageDatafields(cube_data, report_data);
    
    ChangeMainPageStateOverlay("default", cube_data, 0);

    BindMainPageElements(cube_data);

    // $(document).click(function (e) {
    //     if ($(`#${state_overlay_container_id}`).children(":first").hasClass("state_overlay")) {
    //         if (!$(e.target).parents(".state_overlay").length && !$(e.target).hasClass("state_overlay")) {
    //             ChangeMainPageStateOverlay("default", cube_data, 400);
    //         }
    //     }
    // });
}

function ResetMainPageDatafields(cube_data, report_data){
    let last_report = report_data[0];
    let sincePlanting = moment(cube_data["planting_datetime"]).locale("ru").fromNow();
    let sinceWatering = moment(last_report["last_watering_datetime"]).locale("ru").fromNow();
    let cooler_status = cube_data["cooler_status"] > 0 ? "включена" : "отключена";
    
    $(`#${name_datafield_id}`).text(cube_data["name"]);
    $(`#${description_datafield_id}`).text(cube_data["description"]);
    $(`#${temperature_datafield_id}`).text(`Температура: ${last_report["temperature"]} °C`);
    $(`#${humidity_air_datafield_id}`).text(`Влажность воздуха: ${last_report["humidity_air"]}%`);
    $(`#${moisture_soil_datafield_id}`).text(`Влажность почвы: ${last_report["moisture_soil"]}%`);
    $(`#${light_status_datafield_id}`).text(`Процент внешнего освещения: ${last_report["light_status"]}%`);
    $(`#${ph_datafield_id}`).text(`pH: ${last_report["ph"]}`);
    $(`#${last_watering_datetime_datafield_id}`).text(`Последний полив: ${sinceWatering}`);
    $(`#${cooler_status_datafield_id}`).text(`Вентиляция: ${cooler_status}`);
    $(`#${planting_datetime_datafield_id}`).text(`Посажено: ${sincePlanting}`);
}

function ResetMainPageStatusDatafields(cube_data){
    $(`#${overlay_light_status_datafield_id}`).text(`${cube_data["light_status"]}%`);
    $(`#${overlay_temperature_datafield_id}`).text(`${cube_data["temperature"]} °C`);
    $(`#${overlay_water_volume_datafield_id}`).val(`${cube_data["water_volume"]}`);
    $(`#${overlay_cooler_status_datafield_id}`).text(`${cube_data["cooler_status"]}%`);
}

function BindMainPageElements(cube_data){
    $(`.overlay_change_btn`).on("click", function(){
        let href = $(this).attr('href');
        ChangeMainPageStateOverlay(href, cube_data, 400);
    });

    $(`.status_update_btn`).on("click", function(){
        let href = $(this).attr('href');
        switch(href){
            case "#light_status":
                UpdateCubeStates("light_status", cube_data["light_status"]);
                break;
            case "#temperature_status":
                UpdateCubeStates("temperature", cube_data["temperature"]);
                break;
            case "#water_status":
                UpdateCubeStates("water_volume", $(`#${overlay_water_volume_datafield_id}`).val());
                break;
            case "#cooler_status":
                UpdateCubeStates("cooler_status", cube_data["cooler_status"]);
        }
    });

    $(`.light_status_change_btn`).on("click", function(){
        let href = $(this).attr('href');
        switch(href){
            case "#+":
                if (cube_data["light_status"] < 100) {
                    cube_data["light_status"]++;
                }
                break;
            case "#-":
                if (cube_data["light_status"] > 0) {
                    cube_data["light_status"]--;
                }
                break;
        }
        ResetMainPageStatusDatafields(cube_data);
    });

    $(`.temperature_status_change_btn`).on("click", function(){
        let href = $(this).attr('href');
        console.log(cube_data["temperature"]);
        switch(href){
            case "#+":
                if (cube_data["temperature"] < 100) {
                    cube_data["temperature"] = (parseFloat(cube_data["temperature"]) + parseFloat(0.1)).toFixed(2);
                }
                break;
            case "#-":
                if (cube_data["temperature"] > 0) {
                    cube_data["temperature"] = (parseFloat(cube_data["temperature"]) - parseFloat(0.1)).toFixed(2);
                }
                break;
        }
        ResetMainPageStatusDatafields(cube_data);
    });

    $(`.cooler_status_change_btn`).on("click", function(){
        let href = $(this).attr('href');
        console.log(cube_data["cooler_status"]);
        switch(href){
            case "#+":
                if (cube_data["cooler_status"] < 100) {
                    cube_data["cooler_status"]++;
                }
                break;
            case "#-":
                if (cube_data["cooler_status"] > 0) {
                    cube_data["cooler_status"]--;
                }
                break;
        }
        ResetMainPageStatusDatafields(cube_data);
    });
}

function ChangeMainPageStateOverlay(href, cube_data, duration){
    $(`#${state_overlay_container_id}`).fadeOut(duration, function(){
        switch(href){
            case "#light":
                $(`#${state_overlay_container_id}`).html($(`#${state_overlay_light_id}`)[0].outerHTML);
                break;
            case "#temperature":
                $(`#${state_overlay_container_id}`).html($(`#${state_overlay_temperature_id}`)[0].outerHTML);
                break;
            case "#water":
                $(`#${state_overlay_container_id}`).html($(`#${state_overlay_water_id}`)[0].outerHTML);
                break;
            case "#cooler":
                $(`#${state_overlay_container_id}`).html($(`#${state_overlay_cooler_id}`)[0].outerHTML);
                break;
            case "#default":
            default:
                $(`#${state_overlay_container_id}`).html($(`#${state_overlay_default_id}`)[0].outerHTML);
                break;
        }

        ResetMainPageStatusDatafields(cube_data);

        BindMainPageElements(cube_data);

        $(`#${state_overlay_container_id}`).fadeIn(duration);
    });
}