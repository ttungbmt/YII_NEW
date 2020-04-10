import React, {Component, Fragment} from 'react'
import {map, isEmpty, get, isArray} from 'lodash'
import {Map} from 'react-maps'

// import * as ReactMap from 'react-maps'
//
// const {Map: LeafletMap, Marker} = ReactMap
// import {getCoords, flip, bboxPolygon, bbox, feature} from '@turf/turf'

class MapContainer extends Component {
    static displayName = 'MapContainer'

    constructor(props) {
        super(props)
        // this.$el = $(props.element)
    }

    getOptions(){
        const {element, filter, merge, children, current, ...props} = this.props
        return props
    }

    getChildren(children){
        if(isEmpty(children)) return null

        return isArray(children) ? map(children, (item, k) => this._renderChild(item, k)) : this._renderChild(children)
    }

    onDragEng = ({target}) => {
        const {lat, lng} = target.getLatLng()
        $('.pt-lat').val(lat)
        $('.pt-lng').val(lng)
    }

    componentDidMount() {
        const {current} = this.props
        if(current){
            this.map.fitBounds(L.latLngBounds(getCoords(flip(bboxPolygon(bbox(turf.feature(current)))))))
            this.map.setZoom(17)
        }
    }


    _renderChild(child, k){
        const {_type, children, ...props} = child
        const Component = get(ReactMap, _type ? _type : k)
        if(!Component){
            console.warn('Not found Component: '+_type)
            return null
        }

        if(_type === 'Marker'){
            props.onDragEnd = this.onDragEng
        }

       return (
           <Component {...props} key={k}>
               {this.getChildren(children)}
           </Component>
       )
    }

    render(){
        return (
            <div>
                Thanh Tùng
            </div>
        )
    }

    // render() {
    //     // return <div>123</div>
    //     // const {children, current} = this.props
    //     // const mapOption = this.getOptions()
    //     //
    //     // // if(current){
    //     // //     mapOption.bounds = L.latLngBounds(getCoords(flip(bboxPolygon(bbox(turf.feature(current))))))
    //     // //     mapOption.zoom = 15
    //     // // }
    //     //
    //     // return (
    //     //     <LeafletMap {...mapOption} ref={el => this.map = el.leafletElement}>
    //     //         {this.getChildren(children)}
    //     //     </LeafletMap>
    //     // )
    // }
}

export default MapContainer