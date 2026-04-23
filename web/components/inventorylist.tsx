export default function listavailable(){
    return(
        <div>
            <h1>List Device Available</h1>
            <table className="min-w-full divide-y divide-gray-200">
                <tr className="bg-gray-50">
                    <th>Label</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>Asus Vivobook</td>
                    <td>Laptop</td>
                    <td>Available</td>
                </tr>
            </table>
        </div>
    );
}