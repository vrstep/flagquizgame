export default function Home() {
    return (
        <div className="flex flex-col items-center justify-center min-h-screen py-2 gap-10">
            <h1 className="text-4xl font-bold">Welcome to Flag Guess Game!</h1>
            <div className="flex flex-col gap-4">
                <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-7 rounded">
                    Login
                </button>
                <button className="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-7 rounded">
                    Sign Up
                </button>
            </div>
        </div>
    );
}
