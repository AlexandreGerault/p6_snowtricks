import useInfiniteTrickSWR from "./useInfiniteTrickSWR";
import TrickOverview from "./TrickOverview";
import {useCallback} from "react";

export default function TrickList() {
    const {tricks, loadMore} = useInfiniteTrickSWR();

    const handleClick = useCallback(async () => {
        await loadMore()
    }, [loadMore]);

    return (
        <div className="space-y-4">
            <section className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                {tricks && tricks.map((trick) => (
                    <TrickOverview
                        key={trick.id}
                        id={trick.id}
                        category={trick.category}
                        name={trick.name}
                        image={trick.image}
                        url={trick.url}
                        editUrl={trick.editUrl}
                        deleteUrl={trick.deleteUrl}
                    />
                ))}
            </section>

            <div className="flex justify-center">
                <button type="button" onClick={handleClick} className="px-6 py-4 rounded bg-blue-500 text-white">
                    Voir plus
                </button>
            </div>
        </div>
    );
}
