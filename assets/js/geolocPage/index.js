import React from 'react'
import ReactDom from 'react-dom'

import MapPage from './MapPage.jsx'

const mapElement = document.querySelector('#map-elem');
const getData = (name, json = true) => {
    const value = mapElement.getAttribute(`data-${name}`);
    return json ? JSON.parse(value) : value;
};

const element = React.createElement(MapPage, {
    content: 'hello world',
});

console.log("hello");

ReactDom.render(element, document.getElementById('map-elem'));
