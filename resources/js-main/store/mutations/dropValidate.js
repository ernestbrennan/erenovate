export function validateDropZone(state) {
    const array = state.dropZoneValidMap.drops;
    const error = function(){
        let container = $('.contract-comp__body'),
            scrollTo = $('.contract-comp__body .contract-draft__batch-not-submited').eq(0);
        if (typeof scrollTo !== 'undefined') {
            container.animate({
                scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() - 200
            }, 1000);
        }
    };

    if(array.length === 0){
        state.dropZoneValidMap.valid = true;
        return false;
    }
    for(let i = 0; i < array.length;i++){
       if(array[i].invalid === true){
           state.dropZoneValidMap.valid = false;
           error()
           return false;
       }
    }
    state.dropZoneValidMap.valid = true;
    return true;
}

export function setValidDropZone(state, drop_zone) {

    for (let i = 0 ; i < state.dropZoneValidMap.drops.length; i++){
        if(state.dropZoneValidMap.drops[i].id === drop_zone.id){
            state.dropZoneValidMap.drops[i].invalid = drop_zone.invalid;
            break;
        }
    }

}
export function removeValidDropZone(state, drop_id) {
    for( let i = 0; i < state.dropZoneValidMap.drops.length; i++){
        if(state.dropZoneValidMap.drops[i].id === drop_id){
            state.dropZoneValidMap.drops.splice(i, 1);
            break;
        }
    }
    return true;
}
export function mountedValidDropZone(state,drop_obj){
    state.dropZoneValidMap.drops.push(drop_obj);
    return true;
}