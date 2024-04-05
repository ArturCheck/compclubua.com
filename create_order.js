// Створення об'єктів для зберігання цін компонентів
const componentPrices = {
    motherboard: 150,
    case: 50,
    ram: 80,
    storage: 100,
    gpu: 200,
    psu: 70,
    cooler: 30,
    cpu: 180
};

// Функція для розрахунку загальної ціни компонентів
function calculatePrice() {
    let totalPrice = 0;

    // Зчитування значень вибраних компонентів і розрахунок ціни
    for (const component in componentPrices) {
        const quantity = parseInt(document.getElementById(`${component}Quantity`).value) || 0;
        totalPrice += quantity * componentPrices[component];
    }

    // Виведення результату
    alert(`Total Price: $${totalPrice}`);
}

// Функція для створення замовлення
function createOrder() {
    const orderDetails = {};

    // Зчитування значень вибраних компонентів
    for (const component in componentPrices) {
        const quantity = parseInt(document.getElementById(`${component}Quantity`).value) || 0;
        orderDetails[component] = quantity;
    }

    // Виведення деталей замовлення (можна здійснити AJAX-запит для збереження в базу даних)
    alert(`Order Details: ${JSON.stringify(orderDetails)}`);
}