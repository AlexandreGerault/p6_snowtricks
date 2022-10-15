import useSWRInfinite from 'swr/infinite'
import axios from 'axios'
import {TrickOverviewType} from "./TrickType";

const fetcher = (url: string) => axios.get(url).then(res => res.data)

function getKey(pageIndex: number, previousPageData: any) {
    if (previousPageData && !previousPageData.length) return null
    return `/api/tricks?page=${pageIndex}&limit=10`
}

type HookData = TrickOverviewType[];

export default function useInfiniteTrickSWR() {
    const {data, error, size, setSize} = useSWRInfinite<HookData>(getKey, fetcher);

    const loadMore = () => setSize(size + 1);

    console.log({data});

    return {
        tricks: data ? data.reduce((acc, val) => [...acc, ...val], [] as TrickOverviewType[]) : [],
        error,
        loadMore
    }
}
