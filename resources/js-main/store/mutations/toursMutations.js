export function cookieTourPush(state, tour_title){
        state.first_enter_tour[tour_title] = false
        const current_state = state.first_enter_tour
        $cookies.set('is_first_object', current_state, '24d')
}
