import { useEffect, useState } from "react";
import axios from "axios";

export default function UserList() {
    const [users, setUsers] = useState([]);
    const [userId, setUserId] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const url = "http://localhost/flagsgame/pages/api/main.php";

    // Read: Fetch users
    useEffect(() => {
        axios
            .get(url)
            .then((response) => setUsers(response.data))
            .catch((error) => console.error(error));
    }, []);

    // Update: Update a user
    const handleUpdateUser = (e) => {
        e.preventDefault();
        let data = {
            id: userId,
            email: email,
            password: password,
        };
        axios
            .put(url, data)
            .then((response) => {
                alert("User updated successfully");
                // Refresh user list
                axios.get(url).then((response) => setUsers(response.data));
            })
            .catch((error) => alert(error));
    };

    return (
        <div className="overflow-x-hidden">
            <div className="flex flex-col dark:bg-gray-800">
                <div className="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div className="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div className="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg dark:border-gray-700">
                            <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead className="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            scope="col"
                                            className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-200"
                                        >
                                            ID
                                        </th>
                                        <th
                                            scope="col"
                                            className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-200"
                                        >
                                            Email
                                        </th>
                                        <th
                                            scope="col"
                                            className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-200"
                                        >
                                            Password
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    {users.map((user, index) => (
                                        <tr
                                            key={index}
                                            className="dark:bg-gray-800"
                                        >
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900 dark:text-gray-200">
                                                    {user.id}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900 dark:text-gray-200">
                                                    {user.email}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900 dark:text-gray-200">
                                                    {user.password}
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div className="mt-5">
                <h2 className="text-2xl font-bold mb-5">Update User</h2>
                <label
                    className="block text-sm font-bold mb-2"
                    htmlFor="userId"
                >
                    User ID
                </label>
                <input
                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="userId"
                    type="text"
                    value={userId}
                    onChange={(e) => setUserId(e.target.value)}
                    placeholder="User ID"
                />
                <label
                    className="block text-sm font-bold mb-2 mt-4"
                    htmlFor="email"
                >
                    Email
                </label>
                <input
                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="email"
                    type="text"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="Email"
                />
                <label
                    className="block text-sm font-bold mb-2 mt-4"
                    htmlFor="password"
                >
                    Password
                </label>
                <input
                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="password"
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    placeholder="Password"
                />
                <button
                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4"
                    onClick={handleUpdateUser}
                >
                    Update
                </button>
            </div>
        </div>
    );
}
