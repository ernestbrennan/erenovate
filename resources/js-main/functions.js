function formatPrice(price){
    return parseFloat(String(price).replace(",", "") || 0)
}
