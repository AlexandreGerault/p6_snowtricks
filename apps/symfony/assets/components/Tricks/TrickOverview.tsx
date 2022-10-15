import {TrickOverviewType} from "./TrickType";

type Props = TrickOverviewType;

export default function TrickOverview({category, name, image, url, editUrl, deleteUrl}: Props) {
    return (
        <article className="trick flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div className="flex-shrink-0">
                <img className="h-48 w-full object-cover"
                     src={`/storage/uploads/tricks/${image}`}
                     alt={`Photo d'une figure de ${name}`}
                />
            </div>
            <div className="flex-1 bg-white p-6 flex flex-col justify-between">
                <div className="flex-1">
                    <p className="text-sm font-medium text-indigo-600">
                        {category}
                    </p>
                    <div className="flex gap-2 justify-between">
                        <a href={url} className="block mt-2 hover:underline">
                            <p className="text-xl font-semibold text-gray-900">
                                {name}
                            </p>
                        </a>
                        {editUrl && deleteUrl && (
                            <div className="flex gap-1">
                                <a href={editUrl}>
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action={deleteUrl} method="post">
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>)}
                    </div>
                </div>
            </div>
        </article>
    );
}
