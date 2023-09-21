let reports_container_id = "reports_container";

function InitializeReportPage(report_data){
    FillReportPageReportContainer(report_data);
}

function FillReportPageReportContainer(report_data){
    let markup = "";

    for(let i = 0; i < report_data.length; i++){
        let report = report_data[i];
        let creation_date = moment(report["creation_datetime"]).locale("ru").format('MMMM Do YYYY, h:mm:ss a');

        markup += HTML("div", {class: "main__card"}, [
            HTML("h1", {class: "main__card-title"}, creation_date),
            HTML("div", {class: "main__card-info"}, [
                HTML("p", {class: "temperature text"}, `Температура: ${report["temperature"]} °C`),
                HTML("p", {class: "air-wet text"}, `Влажность воздуха: ${report["humidity_air"]}%`),
                HTML("p", {class: "land-wet text"}, `Влажность почвы: ${report["moisture_soil"]}%`),
                HTML("p", {class: "light-percent text"}, `Процент освещения: ${report["light_status"]}%`),
                HTML("p", {class: "hydrogen-indicator text"}, `pH: ${report["ph"]}`),
            ])
        ]);
    }

    $(`#${reports_container_id}`).html(markup);
}