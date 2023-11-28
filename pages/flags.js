import { useEffect, useState } from "react";
import axios from "axios";
import Image from "next/image";

export default function FlagList() {
    const [flags, setFlags] = useState([]);
    const [flagId, setFlagId] = useState("");
    const [country, setCountry] = useState("");
    const [code, setCode] = useState("");
    const [image, setImage] = useState("");
    const [updateFlagId, setUpdateFlagId] = useState("");
    const [deleteFlagId, setDeleteFlagId] = useState("");

    const url = "http://localhost/flagsgame/pages/api/flags.php";

    // Read: Fetch flags
    useEffect(() => {
        axios
            .get(url)
            .then((response) => {
                console.log(response.data);
                console.log(response); // Add this line
                setFlags(response.data);
            })
            .catch((error) => console.error(error));
    }, []);

    // Create: Add a new flag
    const handleAddFlag = (e) => {
        e.preventDefault();
        let formData = new FormData();
        formData.append("country", country);
        formData.append("code", code);
        formData.append("image", image);
        axios
            .post(url, formData)
            .then((response) => {
                alert("Flag added successfully");
                // Refresh flag list
                axios.get(url).then((response) => setFlags(response.data));
                // Clear the input fields
                setCountry("");
                setCode("");
                setImage("");
            })
            .catch((error) => alert(error));
    };

    // Update: Update a flag
    const handleUpdateFlag = (e) => {
        e.preventDefault();
        let formData = new FormData();
        formData.append("id", updateFlagId);
        if (country) {
            formData.append("country", country);
        }
        if (code) {
            formData.append("code", code);
        }
        if (image) {
            formData.append("image", image);
        }
        axios
            .put(url, formData)
            .then((response) => {
                alert("Flag updated successfully");
                // Refresh flag list
                axios.get(url).then((response) => setFlags(response.data));
                // Clear the input fields
                setUpdateFlagId("");
                setCountry("");
                setCode("");
                setImage("");
            })
            .catch((error) => alert(error));
    };

    // Delete: Delete a flag
    const handleDeleteFlag = (e) => {
        e.preventDefault();
        let data = {
            id: deleteFlagId,
        };
        axios
            .delete(url, { data })
            .then((response) => {
                alert("Flag deleted successfully");
                // Refresh flag list
                axios.get(url).then((response) => setFlags(response.data));
                // Clear the input field
                setDeleteFlagId("");
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
                                            Country
                                        </th>
                                        <th
                                            scope="col"
                                            className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-200"
                                        >
                                            Code
                                        </th>
                                        <th
                                            scope="col"
                                            className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-200"
                                        >
                                            Image
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    {flags.map((flag, index) => {
                                        let image = `data:image/png;base64,${flag.image}`;
                                        return (
                                            <tr
                                                key={index}
                                                className="dark:bg-gray-800"
                                            >
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900 dark:text-gray-200">
                                                        {flag.id}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900 dark:text-gray-200">
                                                        {flag.country}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900 dark:text-gray-200">
                                                        {flag.code}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <Image
                                                        src={image}
                                                        alt={flag.country}
                                                        width={50}
                                                        height={50}
                                                    />
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div className="mt-5">
                <h2 className="text-2xl font-bold mb-5">Update Flag</h2>
                <label
                    className="block text-sm font-bold mb-2"
                    htmlFor="flagId"
                >
                    Flag ID
                </label>
                <input
                    className="shadow appearance-none border rounded w-full md:w-1/2 lg:w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="flagId"
                    type="text"
                    value={updateFlagId}
                    onChange={(e) => setUpdateFlagId(e.target.value)}
                    placeholder="Flag ID"
                />
                {/* ...similar changes for other input fields... */}
            </div>
            {/* ...existing code... */}
        </div>
    );
}
