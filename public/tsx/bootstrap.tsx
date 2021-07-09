import { h, render } from "preact";
import { ControlPanel } from "./ControlPanel";
import { FeelingsControlPanel } from "./FeelingsControlPanel";
import { ImagePanel, ImageProps } from "./ImagePanel";
import {FeelingsProps, HumanFeelingsPanel} from "./HumanFeelings";

import {startEventProcessing} from "./events";

import "./legacy.js";

function setupControlPanel() {
    let controlPanelElement = document.getElementById("controlPanel");

    if (controlPanelElement === null) {
        console.warn('controlPanel not present.');
        return;
    }

    let params = {};
    if (controlPanelElement.dataset.hasOwnProperty("params_json") === true) {
        let json = controlPanelElement.dataset.params_json;
        params = JSON.parse(json);
    }
    else {
        console.error("params_json not set, cannot create react controls");
        return;
    }

    let controls = {};
    if (controlPanelElement.dataset.hasOwnProperty("controls_json") === true) {
        let json = controlPanelElement.dataset.controls_json;
        controls = JSON.parse(json);
    }
    else {
        console.error("controls_json not set, cannot create react controls");
        return;
    }

    let controlProps = {
        name: "cool working",
        initialControlParams: params,
        controls: controls
    };

    // @ts-ignore: blah blah
    render(
        // @ts-ignore: blah blah
        <ControlPanel {...controlProps} />,
        // @ts-ignore: blah blah
        document.getElementById("controlPanel")
    );
}

function setupHumanFeelings() {
    let element = document.getElementById("human_feelings");
    if (element === null) {
        // console.warn('human_feelings not present.');
        return;
    }

    let params:FeelingsProps = {
    };

    render(<HumanFeelingsPanel {...params} />, element);
}


function setupHumanFeelingsControlPanel()
{
    let controlPanelElement = document.getElementById("feelingsControls");

    if (controlPanelElement === null) {
        // console.warn('feelingsControls not present.');
        return;
    }

    let control_params = {};
    if (controlPanelElement.dataset.hasOwnProperty("params_json") === true) {
        let json = controlPanelElement.dataset.params_json;
        control_params = JSON.parse(json);
    }

    let controlProps = {
        name: "cool working",
        initialControlParams: control_params
    };

    render(
        <FeelingsControlPanel {...controlProps} />,
        controlPanelElement
    );
}

function setupImagePanel() {
    let element = document.getElementById("imagePanel");
    if (element === null) {
        console.warn('imagePanel not present.');
        return;
    }

    if (element.dataset.hasOwnProperty("imagebaseurl") !== true) {
        console.error('imagePanel missing imagebaseurl');
        return;
    }

    if (element.dataset.hasOwnProperty("imagebaseurl") !== true) {
        console.error('imagePanel missing imagebaseurl');
        return;
    }

    let params:ImageProps = {
        imageBaseUrl: element.dataset.imagebaseurl,
        pageBaseUrl: element.dataset.pagebaseurl,
        fullPageRefresh: true
    };

    render(<ImagePanel {...params} />, element);
}

(function(){
    setupImagePanel();
    setupControlPanel();
    setupHumanFeelings();
    setupHumanFeelingsControlPanel();
    startEventProcessing();
})();
