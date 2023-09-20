export default function useGetErrorResponseStatus() {
    function is422(err) {
        return isErrorWithResponseAndStatus(err) && 422 === err.response.status;
    }

    function is500(err) {
        return isErrorWithResponseAndStatus(err) && 500 === err.response.status;
    }

    const isErrorWithResponseAndStatus = function(err) {
        return err.response && err.response.status;
    }

    return {
        is422,
        is500
    }
}
